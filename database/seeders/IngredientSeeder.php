<?php

namespace Database\Seeders;

use App\Models\Allergen;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Label;
use App\Models\Trace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IngredientSeeder extends Seeder
{
    /**
     * Create the array of related data or add to existing field.
     *
     * @return void
     */
    private function createOrAddToRelationArray(int $ingredientID, array &$array, string $key, bool $mapAssociative = false): void
    {
        if ($mapAssociative) {
            $ingredientID = ['ingredient_id' => $ingredientID];
        }
        if (!\array_key_exists($key, $array)) {
            $array[$key] = [$ingredientID];
        } else {
            if (!\in_array($ingredientID, $array[$key])) {
                $array[$key][] = $ingredientID;
            }
        }
    }

    /**
     * Seed the database in chunks.
     *
     * @return void
     */
    private function seedDatabase(array $array, string $model): void
    {
        $chunks = collect($array)->chunk(1000);
        foreach ($chunks as $chunk) {
            $model::insert($chunk->toArray());
        }
    }

    /**
     * Perform an array map on the relation arrays and seed the databases with them.
     */
    private function mapAndSeedRelation(array $array, string $model): void
    {
        $relationNames = array_map(fn ($val) => ['name' => $val], array_keys($array));
        $this->seedDatabase($relationNames, $model);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $ingredients = [];
        $labels = [];
        $categories = [];
        $allergens = [];
        $traces = [];

        // Get json file of ingredients
        $ingredientsInput = json_decode(File::get('database/data/ingredients.json'));

        // Read in the Ingredients
        foreach ($ingredientsInput as $ingredientID => $ingredient) {
            // Create the Ingredient
            $ingredients[] = [
                'id' => ++$ingredientID,
                // Add ingredient name
                'name' => $ingredient->name,
                // Add ingredient references
                'references' => json_encode($ingredient->url),
                // Add ingredients properties
                'energy_kcal_100g' => $ingredient->energy_kcal_100g,
                'carbohydrates_100g' => $ingredient->carbohydrates_100g,
                'sugars_100g' => $ingredient->sugars_100g,
                'proteins_100g' => $ingredient->proteins_100g,
                'fiber_100g' => $ingredient->fiber_100g,
                'salt_100g' => $ingredient->salt_100g,
            ];
            // Check for (and add) allergens
            foreach ($ingredient->allergens as $allergen) {
                // Format allergen string
                $allergen = ucwords(mb_strtolower($allergen), '\' ');
                $this->createOrAddToRelationArray(ingredientID: $ingredientID, array: $allergens, key: $allergen, mapAssociative: true);
            }
            // Check for (and add) traces
            foreach ($ingredient->traces as $trace) {
                // Format trace string
                $trace = ucwords(mb_strtolower($trace), '\' ');
                $this->createOrAddToRelationArray(ingredientID: $ingredientID, array: $traces, key: $trace, mapAssociative: true);
            }
            // Check for (and add) labels
            foreach ($ingredient->labels as $label) {
                // Format label string
                $label = ucwords(mb_strtolower($label), '\' ');
                $this->createOrAddToRelationArray(ingredientID: $ingredientID, array: $labels, key: $label);
            }
            // Check for (and add) categories
            foreach ($ingredient->categories as $category) {
                // Format category string
                $category = ucwords(mb_strtolower($category), '\' ');
                $this->createOrAddToRelationArray(ingredientID: $ingredientID, array: $categories, key: $category);
            }
        }

        // Start a DB transaction
        DB::transaction(function () use ($ingredients, $allergens, $traces, $labels, $categories): void {
            $this->seedDatabase($ingredients, Ingredient::class);
            // Seed and map allergen relations
            $this->mapAndSeedRelation($allergens, Allergen::class);
            Allergen::each(function ($allergen) use ($allergens): void {
                $allergen->ingredients()->attach($allergens[$allergen->name]);
            });
            // Seed and map trace relations
            $this->mapAndSeedRelation($traces, Trace::class);
            Trace::each(function ($trace) use ($traces): void {
                $trace->ingredients()->attach($traces[$trace->name]);
            });
            // Seed and map label relations
            $this->mapAndSeedRelation($labels, Label::class);
            Label::each(function ($label) use ($labels): void {
                $label->ingredients()->sync($labels[$label->name]);
            });
            // Seed and map category relations
            $this->mapAndSeedRelation($categories, Category::class);
            Category::each(function ($category) use ($categories): void {
                $category->ingredients()->sync($categories[$category->name]);
            });
        });
    }
}
