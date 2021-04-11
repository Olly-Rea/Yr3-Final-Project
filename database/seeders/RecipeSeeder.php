<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

// Custom imports
use App\Models\{Ingredient, Instruction, User};
use Exception;
use Illuminate\Support\Facades\{DB, File};


// SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column 'misc_info' at row 1
// (SQL: insert into `recipe_ingredients` (`amount`, `ingred_id`, `measure`, `misc_info`, `recipe_id`)
// values (6, 8459, , minutes until they are soft and dry. shake and stir constantly to prevent them from burning if necessary, reduce or remove from occassionally to let it cool for a few moments before returning it to . stir in kebbeh and when it begins to splutter add ginger, fenugreek, cardamom, 15968))


class RecipeSeeder extends Seeder {

    // DEBUG variables
    private $ingred_count = 0;
    private $successes = 0;

    //  Conversion measurements
    private $measures = ['ozs' => 28.3495, 'ounces' => 28.3495, 'lbs' => 453.592, 'pounds' => 453.592];

    /**
     * Method to check if an ingredient exists in the database
     */
    private function checkExists($name) {
        // Increment counter (FOR DEBUG)
        $this->ingred_count++;

        // Store the length of the ingredient name
        $nameLen = strlen($name);
        // Change ' characters to \' (prevents issues with SQL statements)
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

        // if ingredient already exists in database (and the best match length is > 70% the length of the full ingredient name)
        if ($bestFit != null && $bestPercent > 0.7) {
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
     * Method to tweak the amount and measure data where required
     */
    private function getAmountandMeasure($data) {
        // Convert amount from string to int - Also check for fractions (and convert to doubles if required)
        $numbers = explode(" ", $data->amount);
        $amount = (double) $numbers[0];
        if (count($numbers) > 1) {
            $fraction = explode("/", $numbers[1]);
            $amount += (count($fraction) == 2) ? round($fraction[0] / $fraction[1], 6) : $fraction[0];
        }
        // Perform conversion from imperial to metric
        if(isset($this->measures[$data->measure])) {
            $data->amount = round($amount * $this->measures[$data->measure], -1);
            $data->measure = 'g';
        // Check singulars
        } elseif(isset($this->measures[$data->measure.'s'])) {
            $data->amount = round($amount * $this->measures[$data->measure.'s'], -1);
            $data->measure = 'g';
        // If no measurement conversion made, just set data->amount as the pre-converted amount
        } else {
            $data->amount = $amount;
        }
    }

    /**
     * Method to seed the Recipes database using each JSON data file
     */
    public function seedFromFile($filePath) {
        // Get the Recipes JSON file to seed the database with
        $json = File::get($filePath);
        $recipes = json_decode($json);

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
                    'name' => $recipe->title,
                    'serves' => isset($recipe->serves) ? $recipe->serves : 1,
                ]);

                // Add ingredients
                foreach($ingredients as $prim_name => $prim_data) {
                    // Check if the ingredient already exists in database (and the best match length is > 50% the length of the full ingredient name)
                    if (($bestFit = $this->checkExists($prim_name)) != null) {

                        // Update the amount (and measure if required) values
                        $this->getAmountandMeasure($prim_data);

                        // Attach the ingredient
                        $newRecipe->ingredients()->syncWithoutDetaching([
                            $bestFit->id => ['misc_info' => $prim_data->misc_info, 'amount' => $prim_data->amount, 'measure' => $prim_data->measure]
                        ]);

                        // Loop through the ingredients alternatives
                        foreach($prim_data->alternatives as $alt_name => $alt_data) {
                            // Check if the ingredient exists in the database
                            if (($alternative = $this->checkExists($alt_name)) != null) {
                                // Check there are no duplicate alternatives under the main Ingredient (and that the alternative is not the same ingredient)
                                $duplicate = $bestFit->alternatives->contains('name', $alternative->name) && $alternative != $bestFit;
                                // And if not, add the ingredient to the list of the main Ingredient's alternatives
                                if(!$duplicate) {

                                    // Update the amount (and measure if required) values
                                    $this->getAmountandMeasure($alt_data);

                                    // Make the alterative ingredient link
                                    $bestFit->alternatives()->syncWithoutDetaching([
                                        $alternative->id => ['recipe_id' => $newRecipe->id, 'misc_info' => $alt_data->misc_info,
                                            'amount' => $alt_data->amount, 'measure' => $alt_data->measure]
                                    ]);
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
                if($count > 0 && $count % 500 == 0) {
                    echo($this->successes/$this->ingred_count."\n");
                }

            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Remove all recipes (needed while debugging)
        DB::table('recipes')->delete();
        DB::table('recipe_ingredients')->delete();

        // The list of filenames to use
        $fileNames = ['recipes_1.json','recipes_2.json','recipes_3.json','recipes_4 (wip).json'];
        // Loop through and seed from each JSON file provided
        foreach($fileNames as $fileName) {
            echo("Seeding from ".$fileName."\n");
            $this->seedFromFile('database/data/'.$fileName);
        }
    }

}
