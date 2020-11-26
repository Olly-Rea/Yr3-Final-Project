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
        // Recipe::truncate();

        // Get the Recipes JSON file to seed the database with
        $json = File::get('database/data/recipes.json');
        $recipes = json_decode($json);

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
                'name' => ucwords(strtolower($recipe->title), "\" "),
                'serves' => isset($recipe->serves) ? $recipe->serves : 1,
                // 'created_at' => $create_date,
                // 'updated_at' => $update_date
            ]);

            // Check that the recipe includes ingredients (as if not, add default)
            if (isset($recipe->ingredients)) {
                // Add ingredients
                foreach($recipe->ingredients as $ingredient) {

                    // TODO Get the name of the ingredient

                    // TODO Get the value of the ingredient

                    // If ingredient already exists, add it to the recipes relationships
                    $exists = Ingredient::where('name', 'LIKE', '%'.$ingredient.'%')->first();
                    if ($exists != null) {
                        $newRecipe->ingredients()->sync($exists, false);
                    // Otherwise create a new ingredient...
                    } else {
                        $newIngredient = Ingredient::create([
                            'name' => ucwords($ingredient),
                            'url' => null,
                            'products' => null,
                        ]);
                        // ...and add it to the recipes relationships
                        $newRecipe->ingredients()->sync($newIngredient, false);
                    }
                }
            } else {
                $newIngredient = Ingredient::where('name', '=', 'Text Only')->first();
                // add it to the recipes relationships
                $newRecipe->ingredients()->sync($newIngredient, false);
            }

            // Check that the recipe includes directions (as if not, add default)
            if (isset($recipe->directions)) {
                // Add instructions for the recipe to the database
                foreach($recipe->directions as $instruction) {
                    Instruction::create([
                        'recipe_id' => $newRecipe->id,
                        'content' => ucwords($instruction),
                    ]);
                }
            }
            // else {
            //     Instruction::create([
            //         'recipe_id' => $newRecipe->id,
            //         'content' => 'None',
            //     ]);
            // }

            // echo($newRecipe . "\n");

        }

        // \App\Models\Recipe::factory(100)->create();
    }
}
