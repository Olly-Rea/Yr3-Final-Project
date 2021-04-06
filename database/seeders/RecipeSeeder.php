<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

// Custom imports
use App\Models\{Ingredient, Instruction, User};
use Exception;
use Illuminate\Support\Facades\{DB, File};


// SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '12868-2526-4211' for key 'alternatives.alternatives_recipe_id_ingred_id_altrnt_id_unique'
// (SQL: insert into `alternatives` (`altrnt_id`, `ingred_id`, `recipe_id`) values (4211, 2526, 12868))


class RecipeSeeder extends Seeder {

    // DEBUG variables
    private $ingred_count = 0;
    private $successes = 0;

    // Method to check if an ingredient exists in the database
    private function checkExists($name) {
        // Increment counter (FOR DEBUG)
        $this->ingred_count++;

        // Store the length of the ingredient name
        $nameLen = strlen($name);

        $name = str_replace('\'', '\\\'', $name);

        // Check if ingredient (or more common variation of ingredient) exists (refresh the list each time)
        $exists = Ingredient::where('name', $name)->get()->fresh();
        if (count($exists) == 0) {
            // If there is no exact match, do a more advanced query
            $exists = Ingredient::whereRaw('\''.$name.'\' LIKE CONCAT(\'%\', name, \'%\')')->get()->fresh();
        }

        // Set the attribute for best percentage match at 0
        $bestPercent = 0;

        // Loop through all matches to find the best match
        foreach($exists as $match) {
            // Check if the match is a better fit than any previous matches
            $matchPercent = 1 - strlen(str_replace(strtolower($match->name), '', strtolower($name)))/$nameLen;
            // if so, make it the new 'best match'
            if($matchPercent > $bestPercent) {
                $bestFit = $match;
                $bestPercent = $matchPercent;
            }
        }

        // Ensure bestFit has a minimum declaration of null
        $bestFit = isset($bestFit) ? $bestFit : null;

        // if ingredient already exists in database (and the best match length is > 50% the length of the full ingredient name)
        if ($bestFit != null && $bestPercent > 0.5) {
            // Increment Successes and output message (DEBUG)
            $this->successes++;
            // echo("  match found!  |  " . $name . " -> " . $bestFit->name . " | Match: " . round($bestPercent*100, 1) . "%\n");
        // Otherwise output failure
        }
        // else {
        //     // Check that there is actually something in bestFit at all
        //     $bestFitName = isset($bestFit) ? $bestFit->name : $name;
        //     echo("NO match found! |  " . $name . " -> " . $bestFitName . " | Match: " . round($bestPercent*100, 1) . "%\n");// (Creating new Ingredient USING " . $bestFit->name . ")\n");
        // }

        // Return the ingredient's bestFit (or null)
        return $bestFit;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        // Remove all recipes (debug)
        DB::table('recipes')->delete();
        DB::table('recipe_ingredients')->delete();

        // Get the Recipes JSON file to seed the database with
        $json = File::get('database/data/recipes.json');
        $recipes = json_decode($json);

        echo(count($recipes) . "\n");

        // Seed Recipe Database
        foreach($recipes as $count => $recipe) {

            // get the 'author' of the recipe
            $author = User::inRandomOrder()->first();

            // Get each object from the list of ingredients
            $ingredients = get_object_vars($recipe->ingredients);
            $instructions = $recipe->directions;

            // Check that the recipe includes ingredients and instructions, as if not; exclude it from the database
            // (recipes.json was checked thoroughly beforehand, but due to time constraints, not all issues have been solved)
            if ((isset($ingredients) && count($ingredients) > 1) && count($instructions) > 1) {

                // Create the new recipe
                $newRecipe = Recipe::create([
                    'user_id' => $author->id,
                    'name' => ucwords($recipe->title, '\' '),
                    'serves' => isset($recipe->serves) ? $recipe->serves : 1,
                ]);

                // Add ingredients
                foreach($ingredients as $prim_name => $prim_data) {
                    // Check if the ingredient already exists in database (and the best match length is > 50% the length of the full ingredient name)
                    if (($bestFit = $this->checkExists($prim_name)) != null) {

                        // Convert from string to int - Also check for fractions (and convert to doubles if required)
                        $numbers = explode(" ", $prim_data->amount);
                        $amount = (double) $numbers[0];
                        if (count($numbers) > 1) {
                            $fraction = explode("/", $numbers[1]);
                            $amount += (count($fraction) == 2) ? round($fraction[0] / $fraction[1], 6) : $fraction[0];
                        }

                        // // Convert from imperial to metric measurements (because sense?)
                        // $amountArr = explode(' ', $ingredArr[0]);
                        // // If no measurement given, return just the amount
                        // if (count($amountArr) == 1) {
                        //     $value = intval($amountArr[0]);
                        //     // Otherwise, convert where appropriate
                        // } else {
                        //     $value = intval($amountArr[0]);
                        //     $measurement = $amountArr[1];
                        //     // Checks to convert from imperial to metric
                        //     if ($measurement == 'ounces' || $measurement == 'ounce') {
                        //         $measurement = 'g';
                        //         $value = round($value * 28.3495, -1);
                        //     }
                        //     if ($measurement == 'lb') {
                        //         $measurement = 'g';
                        //         $value = round($value * 453.592, -1);
                        //     }
                        //     // TODO Fix measurement conversions
                        //     // (converting null measurements to grams?)
                        // }
                        // $name = $ingredArr[1];

                        // Attach the ingredient
                        $newRecipe->ingredients()->attach($bestFit, [
                            'misc_info' => $prim_data->misc_info,
                            'amount' => $amount,
                            'measure' => $prim_data->measure
                        ]);

                        // Loop through the ingredients alternatives
                        foreach($prim_data->alternatives as $alt_name => $alt_data) {
                            // Check if the ingredient exists in the database
                            if (($alternative = $this->checkExists($alt_name)) != null) {
                                // Check there are no duplicate alternatives under the main Ingredient (and that the alternative is not the same ingredient)
                                $duplicate = $bestFit->alternatives->contains('name', $alternative->name) && $alternative != $bestFit;
                                // And if not, add the ingredient to the list of the main Ingredient's alternatives
                                if(!$duplicate) {
                                    $bestFit->alternatives()->attach($alternative, ['recipe_id' => $newRecipe->id]);
                                }
                            }
                        }

                    }
                }

                // Add each of the recipe's instructions to the database
                foreach($instructions as $instruction) {
                    Instruction::create([
                        'recipe_id' => $newRecipe->id,
                        'content' => ucfirst(str_replace('|', '', $instruction)),
                    ]);
                }

                // DEBUG output current ingredient-matching success rate
                if($count > 0 && $count % 250 == 0) {
                    echo($this->successes/$this->ingred_count."\n");
                }

            }
        }

    }

}
