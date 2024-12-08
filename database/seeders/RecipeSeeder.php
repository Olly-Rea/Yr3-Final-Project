<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    // DEBUG variables
    private $ingred_count = 0;
    private $successes = 0;

    // Conversion measurements
    private $measures = [
        'ozs' => 28.3495,
        'ounces' => 28.3495,
        'lbs' => 453.592,
        'pounds' => 453.592,
    ];

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
     * Method to check if an ingredient exists in the database.
     */
    private function checkExists(string $name): ?int
    {
        static $ingredients, $ingredientsFlipped;
        $ingredients ??= array_map(
            fn ($ingredient) => $ingredient['name'],
            Ingredient::select('name')->get()->toArray()
        );
        $ingredientsFlipped ??= array_flip($ingredients);

        // Increment counter (FOR DEBUG)
        ++$this->ingred_count;

        // Check for empty string
        if ($name === '') {
            return null;
        }

        // Method to convert name to singular (if plural and not in ingredients)
        if (\array_key_exists($name, $ingredientsFlipped)) {
            return $ingredientsFlipped[$name];
        } else {
            // Initialise $bestFitID as null
            $bestFitID = null;
            // Try plural to singular conversion
            if (substr($name, -1) === 's') {
                $singularName = substr($name, -3) === 'ies' ? (rtrim($name, 'ies') . 'y') : rtrim($name, 's');
                if (\array_key_exists($singularName, $ingredientsFlipped)) {
                    $bestFitID = $ingredientsFlipped[$singularName];
                }
            } else {
                // If there is no exact match, do a wider check
                $exists = array_filter($ingredients, fn ($ingredient) => str_contains($ingredient, $name));
                // Set the attribute for best percentage match at 0
                $bestPercent = 0;
                // Loop through all matches to find the best match
                foreach ($exists as $id => $match) {
                    // Check if the match is a better fit than any previous matches
                    $matchPercent = 1 - \strlen(str_replace(strtolower($match), '', strtolower($name))) / \strlen($name);
                    // if so, make it the new 'best match'
                    if ($matchPercent > $bestPercent) {
                        $bestFitID = $id + 1;
                        $bestPercent = $matchPercent;
                    }
                }

                // if ingredient already exists in database (and the best match length is > 70% the length of the full ingredient name)
                if ($bestFitID !== null && $bestPercent > 0.7) {
                    // Increment Successes and output message (DEBUG)
                    ++$this->successes;
                    // echo("  match found!  |  " . $name . " -> " . $bestFitID->name . " | Match: " . round($bestPercent*100, 1) . "%\n");
                }
                // else {
                //     // Check that there is actually something in bestFitID at all
                //     $bestFitName = isset($bestFitID) ? $bestFitID->name : $name;
                //     echo("NO match found! |  " . $name . " -> " . $bestFitName . " | Match: " . round($bestPercent*100, 1) . "%\n");// (Creating new Ingredient USING " . $bestFitID->name . ")\n");
                // }
            }

            // Return the ingredient's bestFitID (or null)
            return $bestFitID;
        }
    }

    /**
     * Method to tweak the amount and measure data where required.
     *
     * @param mixed $data
     */
    private function getAmountandMeasure(&$data): void
    {
        // Convert amount from string to int - Also check for fractions (and convert to doubles if required)
        $numbers = explode(' ', $data['amount']);
        $amount = (float) $numbers[0];
        if (\count($numbers) > 1) {
            $fraction = explode('/', $numbers[1]);
            $amount += (\count($fraction) === 2) ? (float) round($fraction[0] / $fraction[1], 6) : (float) $fraction[0];
        }
        // Perform conversion from imperial to metric
        if (isset($this->measures[$data['measure']])) {
            $data['amount'] = round($amount * $this->measures[$data['measure']], -1);
            $data['measure'] = 'g';
        // Check singulars
        } elseif (isset($this->measures[$data['measure'] . 's'])) {
            $data['amount'] = round($amount * $this->measures[$data['measure'] . 's'], -1);
            $data['measure'] = 'g';
        // If no measurement conversion made, just set data->amount as the pre-converted amount
        } else {
            $data['amount'] = $amount;
        }
    }

    /**
     * Map ingredients to the recipe.
     */
    private function mapRecipeIngredients(int $recipeID, array $recipeIngredients, array &$ingredients, array &$alternatives): void
    {
        // Add each ingredient to the 'recipeIngredients' array
        foreach ($recipeIngredients as $primaryName => $primaryData) {
            // Check if the ingredient already exists in database (and the best match length is > 50% the length of the full ingredient name)
            $bestFitID = $this->checkExists($primaryName);
            if ($bestFitID !== null) {
                // Update the amount (and measure if required) values
                $this->getAmountandMeasure($primaryData);

                // Add the ingredient to the 'ingredients' array
                $ingredients[] = [
                    'recipe_id' => $recipeID,
                    'ingredient_id' => $bestFitID + 1,
                    'misc_info' => $primaryData['misc_info'],
                    'amount' => $primaryData['amount'],
                    'measure' => $primaryData['measure'],
                ];

                // // Loop through the ingredients alternatives
                // foreach ($primaryData['alternatives'] as $alternativeName => $alternativeData) {
                //     // Check if the ingredient exists in the database
                //     if (($alternative = $this->checkExists($alternativeName)) !== null) {
                //         // Check there are no duplicate alternatives under the main Ingredient (and that the alternative is not the same ingredient)
                //         $duplicate = $bestFit->alternatives->contains('name', $alternative->name) && $alternative !== $bestFit;
                //         // And if not, add the ingredient to the list of the main Ingredient's alternatives
                //         if ($duplicate) {
                //             continue;
                //         }
                //         // Update the amount (and measure if required) values
                //         $this->getAmountandMeasure($alternativeData);
                //         // Make the alterative ingredient link
                //         $alternatives[] = [
                //             $alternative->id => [
                //                 'recipe_id' => $recipeID,
                //                 'misc_info' => $alternativeData['misc_info'],
                //                 'amount' => $alternativeData['amount'],
                //                 'measure' => $alternativeData['measure']
                //             ]
                //         ];
                //     }
                // }
            }
        }
    }

    /**
     * Map new ratings to the recipe.
     */
    private function mapRatings(int $recipeID, int $authorID, array $authorIDs, array &$ratings): void
    {
        // Array to hold userIDs
        $userIDs = [];
        // Number of ratings to generate
        $range = random_int(10, 64);
        // Create some 'ratings' for the recipe (keep between 10 and 64 to preserve some time)
        for ($i = 0; $i < $range; ++$i) {
            // Get a User (who is not the author, or has already 'rated' this recipe)
            do {
                $userID = $authorIDs[random_int(0, env('NUMBER_OF_USERS') - 1)];
            } while ($userID === $authorID || \in_array($userID, $userIDs));
            // Create the rating
            $ratings[] = [
                'user_id' => $userID,
                'recipe_id' => $recipeID,
                // Random values for each rating
                'spice_value' => random_int(0, 10),
                'sweet_value' => random_int(0, 10),
                'sour_value' => random_int(0, 10),
                'difficulty_value' => random_int(0, 10),
                'time_taken' => random_int(10, 70),
                'out_of_five' => random_int(1, 5),
            ];
            // Add the $userID to the array
            $userIDs[] = $userID;
        }
    }

    /**
     * Method to seed the Recipes database using each JSON data file.
     */
    public function seedFromFile(string $filePath, int &$recipeID): void
    {
        // Get the Recipes JSON file to seed the database with
        $json = file_get_contents($filePath);
        $recipeData = json_decode($json, true, 8, JSON_THROW_ON_ERROR);

        // Set the range of 'author IDs' the recipes can use
        $authorIDs = range(1, env('NUMBER_OF_USERS'));

        $recipesToSeed = [];
        $ingredientsToSeed = [];
        $alternativesToSeed = [];
        $instructionsToSeed = [];
        $ratingsToSeed = [];

        // Seed Recipe Database
        foreach ($recipeData as $recipe) {
            // Get the 'author' of the recipe
            $authorID = $authorIDs[random_int(0, env('NUMBER_OF_USERS') - 1)];

            // Check that the recipe includes recipeIngredients and instructions, as if not; exclude it from the database
            // (recipes.json was checked thoroughly beforehand, but due to time constraints, not all issues have been solved)
            if (!((isset($recipe['ingredients']) && \count($recipe['ingredients']) > 1) && \count($recipe['directions']) > 1)) {
                continue;
            }

            // Add the new core recipe details to the 'recipes' array
            $recipesToSeed[] = [
                'id' => ++$recipeID,
                'name' => trim($recipe['title']),
                'serves' => $recipe['serves'] ?? 1,
                'user_id' => $authorID,
            ];

            // Map the recipe ingredients to the 'ingredients' & 'alternatives' arrays
            $this->mapRecipeIngredients($recipeID, $recipe['ingredients'], $ingredientsToSeed, $alternativesToSeed);

            // Add each of the recipe's instructions to the 'instructions' array
            foreach ($recipe['directions'] as $instruction) {
                $instructionsToSeed[] = [
                    'recipe_id' => $recipeID,
                    'content' => ucfirst(str_replace('|', '', $instruction)),
                ];
            }

            // Map the recipe ratings to the 'ratings' array
            $this->mapRatings($recipeID, $authorID, $authorIDs, $ratingsToSeed);

            // // DEBUG output current ingredient-matching success rate
            // if ($count > 0 && $count % 500 == 0) {
            //     echo ($this->successes / $this->ingred_count . "\n");
            // }
        }

        // Start a DB transaction
        DB::transaction(function () use ($recipesToSeed, $ingredientsToSeed, $instructionsToSeed, $ratingsToSeed): void {
            // Seed recipes
            $this->seedDatabase($recipesToSeed, Recipe::class);
            // Seed the recipe ingredients pivot table
            $ingredientChunks = collect($ingredientsToSeed)->chunk(1000);
            DB::statement('ALTER TABLE recipe_ingredients DROP CONSTRAINT recipe_ingredients_ingredient_id_foreign;');
            foreach ($ingredientChunks as $chunk) {
                DB::table('recipe_ingredients')->insert($chunk->toArray());
            }
            DB::statement('ALTER TABLE recipe_ingredients ADD CONSTRAINT recipe_ingredients_ingredient_id_foreign FOREIGN KEY (ingredient_id) REFERENCES ingredients(id);');
            // Seed recipe instructions and ratings
            $this->seedDatabase($instructionsToSeed, Instruction::class);
            $this->seedDatabase($ratingsToSeed, Rating::class);
        });
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Remove all recipes (needed while debugging)
        echo "  Dropping existing data from DBs... \n";
        DB::table('recipes')->delete();
        DB::table('recipe_ingredients')->delete();
        DB::table('ratings')->delete();

        // The list of filenames to use
        $fileNames = ['recipes_1.json', 'recipes_2.json', 'recipes_3.json'/* 'recipes_4 (wip).json' */];

        // Track recipeIDs here for cross-file increments
        $recipeID = 0;

        // Loop through and seed from each JSON file provided
        foreach ($fileNames as $fileName) {
            echo '  Seeding from ', $fileName, "\n";
            $this->seedFromFile("database/data/$fileName", $recipeID);
        }
    }
}
