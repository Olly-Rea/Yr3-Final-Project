<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Custom imports
use App\Models\{Ingredient, Label, Category, Allergen, Trace};
use Illuminate\Support\Facades\File;

class IngredientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Get json file of ingredients
        $json = File::get('database/data/ingredients.json');
        $ingredients = json_decode($json);

        // Seed Ingredient Database
        foreach($ingredients as $ingredient) {

            // Create the Ingredient
            $newIngredient = Ingredient::create([
                // Add ingredient name
                'name' => $ingredient->name,
                // Add ingredient references
                'references' => $ingredient->url,
                // Add ingredients properties
                'energy_kcal_100g' => $ingredient->energy_kcal_100g,
                'carbohydrates_100g' => $ingredient->carbohydrates_100g,
                'sugars_100g' => $ingredient->sugars_100g,
                'proteins_100g' => $ingredient->proteins_100g,
                'fiber_100g' => $ingredient->fiber_100g,
                'salt_100g' => $ingredient->salt_100g
            ]);

            // Check for (and add) labels
            foreach($ingredient->labels as $label) {
                // Format label string
                $label = ucwords(mb_strtolower($label), '\' ');
                // Check if the Label already exists
                $exists = Label::where('name', '=', $label)->first();
                // If so, attach the existing Label to the Ingredient
                if($exists != null) {
                    // Add the label if it hasn't already been assigned to this ingredient
                    $newIngredient->labels()->syncWithoutDetaching([$exists->id]);
                // Otherwise, create and attach a new Label to the Ingredient
                } else {
                    $newLabel = Label::create([
                        'name' => $label
                    ]);
                    $newIngredient->labels()->attach($newLabel);
                }
            }

            // Check for (and add) categories
            foreach($ingredient->categories as $category) {
                // Format category string
                $category = ucwords(mb_strtolower($category), '\' ');
                // Check if the Category already exists
                $exists = Category::where('name', '=', $category)->first();
                // If so, attach the existing Category to the Ingredient
                if($exists != null) {
                    // Add the category if it hasn't already been assigned to this ingredient
                    $newIngredient->categories()->syncWithoutDetaching([$exists->id]);
                // Otherwise, create and attach a new Category to the Ingredient
                } else {
                    $newCategory = Category::create([
                        'name' => $category
                    ]);
                    $newIngredient->categories()->attach($newCategory);
                }
            }

            // Check for (and add) allergens
            foreach($ingredient->allergens as $allergen) {
                // Format allergen string
                $allergen = ucwords(mb_strtolower($allergen), '\' ');
                // Check if the Allergen already exists
                $exists = Allergen::where('name', '=', $allergen)->first();
                // If so, attach the existing Allergen to the Ingredient
                if($exists != null) {
                    // Add the allergen if it hasn't already been assigned to this ingredient
                    $newIngredient->allergens()->syncWithoutDetaching([$exists->id]);
                // Otherwise, create and attach a new Allergen to the Ingredient
                } else {
                    $newAllergen = Allergen::create([
                        'name' => $allergen
                    ]);
                    $newIngredient->allergens()->attach($newAllergen);
                }
            }

            // Check for (and add) traces
            foreach($ingredient->traces as $trace) {
                // Format trace string
                $trace = ucwords(mb_strtolower($trace), '\' ');
                // Check if the Trace already exists
                $exists = Trace::where('name', '=', $trace)->first();
                // If so, attach the existing Trace to the Ingredient
                if($exists != null) {
                    // Add the trace if it hasn't already been assigned to this ingredient
                    $newIngredient->traces()->syncWithoutDetaching([$exists->id]);
                // Otherwise, create and attach a new Trace to the Ingredient
                } else {
                    $newTrace = Trace::create([
                        'name' => $trace
                    ]);
                    $newIngredient->traces()->attach($newTrace);
                }
            }

        }

    }

}
