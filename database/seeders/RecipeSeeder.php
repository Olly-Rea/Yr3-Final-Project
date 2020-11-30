<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

// Custom import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\User;

class RecipeSeeder extends Seeder
{

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

        $count = 0;
        $successes = 0;

        // Seed Recipe Database
        foreach($recipes as $recipe) {

            // get the "author" of the recipe
            $author = User::inRandomOrder()->first();
            // // Generate a created_at date...
            // $create_date = $this->faker->dateTimeBetween($startDate = $author->created_at, $endDate = 'now');
            // // ...and (possibly) an updated_at date
            // $update_date = null;
            // // 50% chance of more recent updated date
            // if(random_int(0,1) == 1) {
            //     $update_date = $this->faker->dateTimeBetween($startDate = $create_date, $endDate = 'now');
            // }

            $newRecipe = Recipe::create([
                'user_id' => $author->id,
                'name' => ucwords(mb_strtolower($recipe->title), "\" "),
                'serves' => isset($recipe->serves) ? $recipe->serves : 1,
                // 'created_at' => $create_date,
                // 'updated_at' => $update_date
            ]);

            // Check that the recipe includes ingredients (as if not, add default)
            if (isset($recipe->ingredients) && strtolower($recipe->ingredients[0]) != 'text only') {

                // Add ingredients
                foreach($recipe->ingredients as $ingredient) {

                    // Increment counter
                    $count++;

                    // Split into the name and amount of the ingredient (if an amount exists)
                    $alternatives = explode(" (OR) ", $ingredient);
                    // // Initialise variable to hold main ingredient
                    // $mainIngredient = $alternatives[0];

                    // Loop through the "alternatives" array
                    foreach($alternatives as $key => $alternative) {

                        // echo("{".$alternative."}\n");

                        // Split into the name and amount of the ingredient (if an amount exists)
                        $ingredArr = explode(" | ", $alternative);
                        // If there is no amount given, return just the name of the ingredient
                        if(count($ingredArr) == 1) {
                            $name = $ingredArr[0];
                            $value = 1;
                            $measurement = null;
                        // Otherwise...
                        } else {
                            // Convert from imperial to metric measurements (because sense?)
                            $amountArr = explode(" ", $ingredArr[0]);
                            // If no measurement given, return just the amount
                            if(count($amountArr) == 1) {
                                $value = intval($amountArr[0]);
                            // Otherwise, convert where appropriate
                            } else {
                                $value = intval($amountArr[0]);
                                $measurement = $amountArr[1];
                                // Checks to convert from imperial to metric
                                if($measurement == "ounces" || $measurement == "ounce") {
                                    $measurement = 'g';
                                    $value = round($value * 28.3495, -1);
                                }
                                if($measurement == 'lb') {
                                    $measurement = 'g';
                                    $value = round($value * 453.592, -1);
                                }
                                // TODO Fix $measurement conversions
                                // (converting null measurements to grams?)
                            }
                            $name = $ingredArr[1];
                        }

                        // Check if ingredient has any "misc" info
                        $nameArr = explode(" (", $name);
                        // Assign name and (if exists) misc
                        $name = rtrim(ucwords($nameArr[0]), "s");
                        // $name = ucwords($nameArr[0]);
                        $misc = isset($nameArr[1]) ? str_replace(')', '', $nameArr[1]) : null;

                        // Check if ingredient (or more common variation of ingredient) exists
                        $exists = Ingredient::whereRaw("\"".$name."\" LIKE CONCAT(\"%\", name, \"%\")")->get();

                        // Store the length of the recipe ingredient
                        $nameLen = strlen($name);
                        // Set the attribute for best percentage match at 0
                        $bestPercent = 0;
                        // Set the atribute to store any specifics about the ingredient
                        $specifier = null;
                        // Loop through all matches to find the best match
                        foreach($exists as $match) {
                            // Check if the match is a better fit than any previous matches
                            $matchPercent = 1-strlen(str_replace($match->name, "", $name))/$nameLen;
                            // if so, make it the new 'best match'
                            if($matchPercent > $bestPercent) {
                                $bestFit = $match;
                                $bestPercent = $matchPercent;
                                $specifier = str_replace($match->name, "", $name);
                            }
                        }
                        // Correct specifier (if == "")
                        $specifier = $specifier != "" ? $specifier : null;

                        // if ingredient already exists in database (and the best match length is > 40% the length of the full ingredient name)
                        if ($bestFit != null && $bestPercent > 0.4) {

                            // Increment debug
                            $successes++;

                            // echo("  match found!  |  ".$bestFit->name." -> ".$name." | Match: ".round($bestPercent*100, 1)."%\n");

                            // If ingredient is not alternative to other ingredient
                            if($key == 0) {

                                // Check the ingredient is not already in the Recipe's relations
                                $duplicate = $newRecipe->ingredients->contains('name', $bestFit);

                                // $recipe = $newRecipe->load('ingredients');

                                // $duplicate = $recipe->where([
                                //     ['recipe_id', '=', $newRecipe->id],
                                //     ['ingredient_id', '=', $bestFit->id],
                                //     ['amount', '=', $value],
                                //     ['measurement', '=', $measurement],
                                // ])->get();

                                // $duplicate = $newRecipe->ingredients->where([
                                //     // ['recipe_id', '=', $newRecipe->id],
                                //     ['ingredient_id', '=', $bestFit->id],
                                //     // ['amount', '=', $value],
                                //     // ['measurement', '=', $measurement],
                                // ]);

                                // echo(count($duplicate));

                                // And if not, add it to the Recipe's Ingredient relationships
                                if(!$duplicate) {
                                    // Define the ingredient as the main ingredient (not an alternative)
                                    $mainIngredient = $bestFit;
                                    // Add the ingredient to the recipe
                                    $newRecipe->ingredients()->attach($bestFit, ['specifier' => $specifier, 'misc' => $misc, 'amount' => $value, 'measurement' => $measurement]);
                                } else {
                                    echo("duplicate found!\n");
                                }

                            } else {
                                // Check there are no duplicate alterntives under the main Ingredient
                                $duplicate = $mainIngredient->alternatives->where('recipe_id', '=', $newRecipe->id)->contains('name', $bestFit);
                                // And if not, add it to the Recipe's Ingredient relationships
                                if(!$duplicate) {
                                    // Define the ingredient as the main ingredient (not an alternative)
                                    $mainIngredient = $bestFit;
                                    // Add the ingredient to the list of the main Ingredients alternatives
                                    $mainIngredient->alternatives()->attach($bestFit, ['recipe_id' => $newRecipe->id]);
                                } else {
                                    echo("duplicate found!\n");
                                }
                            }

                        // Otherwise create a new ingredient...
                        } else {

                            // echo("NO match found! |  ".$bestFit->name." -> ".$name." | Match: ".round($bestPercent*100, 1)."%\n");

                            // select * from t1 where 'ABCDEFG' LIKE CONCAT('%',column1,'%')

                            // $nameArr = explode(" ", $name);

                            // $specifier = $nameArr[0];
                            // $name = substr($name, strlen($specifier)+1);

                            // $newIngredient = Ingredient::create([
                            //     'name' => ucwords($ingredient),
                            //     'url' => null,
                            //     'products' => null,
                            // ]);
                            // // ...and add it to the recipes relationships
                            // $newRecipe->ingredients()->attach($bestFit, ['misc' => $misc, 'amount' => $value, 'measurement' => $measurement]);
                        }

                    }

                }
            // Otherwise add "Text Only" placeholder ingredient
            } else {
                // Get the placeholder ingredient
                $placeholder = Ingredient::where('name', '=', 'placeholder')->first();
                // add it to the recipes relationships
                $newRecipe->ingredients()->attach($placeholder, ['amount' => 1, 'measurement' => null]);
            }

            // Check that the recipe includes directions (as if not, add default)
            if (isset($recipe->directions)) {
                // Add instructions for the recipe to the database
                foreach($recipe->directions as $instruction) {
                    Instruction::create([
                        'recipe_id' => $newRecipe->id,
                        'content' => ucfirst(str_replace('|', '', $instruction)),
                    ]);
                }
            }

            if($count>0 && $count%100 == 0) {
                echo($successes/$count."\n");
            }

        }

    }
}
