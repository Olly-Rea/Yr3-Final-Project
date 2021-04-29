<?php

namespace App\Http\Controllers;

// Custom imports
use App\{CookbookContainer, MLContainer};
use App\Models\{Recipe, Ingredient, Instruction};
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class RecipeController extends Controller {
    // Number of items to show per page
    var $paginate = 7;

    /**
     * Method to show the User's Cookbook Recipes / Guest's Session Recipes
     */
    public function index(CookbookContainer $cookbook) {
        return view('feed', ['recipes' => $cookbook->getRecipes(false)]);
    }

    /**
     * Method to fetch the next page of paginated data
     */
    public function fetch(Request $request, CookbookContainer $cookbook) {
        // Check that the request is ajax
        if ($request->ajax()) {
            return view('components.recipe-panel', ['recipes' => $cookbook->getRecipes(true)])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to display a single recipe page
     */
    public function show(Recipe $recipe) {
        // Eager load the Recipe's Ingredient Alternatives - specific to this recipe
        $ingredients = $recipe->ingredients()->with(['alternatives' => function ($query) use ($recipe) {
            $query->where('recipe_id', '=', $recipe->id);
        }])->get();
        // Check if a logged in User needs warning about possible allergens
        if (Auth::check()) {
            // Get Recipe Allergens
            $recipeAllergens = $recipe->ingredients()->with(['allergens'])->get()->map(function ($item) {
                if (count($item->allergens)) return $item->allergens;
            })->flatten()->filter(function ($value) { return !is_null($value); });
            // Get the shared allergens and/or traces
            $userAllergens = Auth::user()->profile->allergens->diff(
                Auth::user()->profile->allergens->diff($recipeAllergens)
            );
        } else {
            $userAllergens = [];
        }
        //Return the recipe with it's included ingredients
        return view('recipe.show', ['recipe' => $recipe, 'ingredients' => $ingredients, 'hasAllergens' => $userAllergens]);
    }

    /**
     * Method to allow a user to be given a 'surprise' recipe - personalised if user is logged in
     */
    public function surprise() {
        // Start the query
        $recipes = Recipe::with('ingredients');

        // dd($recipes);

        // Personalise the query to the User (if one logged in)
        if (Auth::check()) {
            // Check against user allergens (if any)
            if(count(Auth::user()->profile->allergens) > 0) {

            }
            // Check against user ingredients (if more than 3)
            $userIngredients = Auth::user()->fridge->ingredients->pluck('id');
            if (count($userIngredients) > 3) {
                // Get recipes containing up to the number of the ingredients the user has
                $recipes = $recipes->whereHas('ingredients', function (Builder $query) use ($userIngredients)  {
                    $query->whereIn('id', $userIngredients);
                }, '>=', 3);
            }
        }
        // get a random recipe from the query
        $recipe = $recipes->inRandomOrder()->first();
        // Return the recipe
        return $this->show($recipe);
    }

    /**
     * Method to return the page for the AI chef results
     */
    public function showAI(MLContainer $mlContainer) {
        // Get the recipe from the machine learning container
        $recipe = $mlContainer->getRecipe();
        // Return it to the view
        return view('ai-chef', ['recipe' => $recipe, 'user' => Auth::user()]);
    }

    /**
     * Method to create a new recipe
     */
    public function create() {
        return view('recipe.edit');
    }

    /**
     * Method to save the changes made to a recipe
     */
    public function save() {

        // Create custom attribute names...
        $attributeNames = array(
            'name' => 'Recipe Name',
            'serves' => 'Serves',
            'ingredient' => 'Ingredients',
            'instruction' => 'Instructions'
        );

        // ...And validate the data
        $validator = Validator::make(request()->all(), [
            'recipe_id' => ['integer'],
            'name' => ['required', 'string', 'max:255'],
            'serves' => ['required', 'integer'],
            // Validate Ingredient array
            'ingredient' => ['required'],
            'ingredient.*.id' => ['required', 'integer'],
            'ingredient.*.amount' => ['required', 'integer'],
            'ingredient.*.measure' => ['string', 'nullable'],
            // Validate Instruction array
            'instruction' => ['required'],
            'instruction.*' => ['string', 'nullable'],
        ], [], $attributeNames);

        // Check if the validator is successful, and either...
        if ($validator->fails()) {

            dd($validator);

            // Output the fail message(s)
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            // Check if the recipe is an existing recipe
            if (request()->has('recipe_id')) {
                // Get the Recipe to use
                $recipe = Recipe::where('id', request('recipe_id'))->first();
            } else {
                // Create the new Recipe (under the Auth Users ID)
                $recipe = Recipe::create([
                    'user_id' => Auth::id(),
                    'name' => request('name'),
                    'serves' => (request('serves') > 0) ? request('serves') : 1
                ]);
            }

            // Clear all attached ingredients
            $recipe->ingredients()->detach();
            // Loop through each ingredient id
            foreach(request('ingredient') as $ingred_info) {
                // Get the ingredient to add
                $ingredient = Ingredient::where('id', $ingred_info['id'])->first();
                // Check to ensure the ingredient exists
                if(isset($ingredient)) {
                    // Add the ingredient
                    $recipe->ingredients()->syncWithoutDetaching([
                        $ingredient->id => [
                            'misc_info' => '',
                            'amount' => $ingred_info['amount'],
                            'measure' => $ingred_info['measure']
                        ]
                    ]);
                }
            }

            // Clear the old instructions
            $recipe->instructions()->delete();
            // Loop through and add each instruction input
            foreach(request('instruction') as $instruction) {
                $recipe->instructions()->create([
                    'content' => $instruction
                ]);
            }

            // Return the User Profile page
            return redirect('/Me')->with('success', 'Recipe created successfully!');
        }

    }

    /**
     * Method to edit a Recipe
     */
    public function edit(Recipe $recipe) {
        // Check that the Recipe is owned by the current User
        if (Auth::id() == $recipe->user_id) {
            return view('recipe.edit', ['recipe' => $recipe]);
        } else {
            abort(403);
        }
    }

    /**
     * Method to delete a Recipe
     */
    public function delete(Recipe $recipe) {
        // Check that the Recipe is owned by the current User
        if (Auth::id() == $recipe->user_id) {
            // Delete the recipe
            $recipe->delete();
            // Return the User Profile page
            return redirect('/Me')->with('success', 'Recipe deleted successfully!');
        } else {
            abort(403);
        }
    }

    /**
     * Method to add an Ingredient input to the Recipe Form
     */
    public function addIngredient(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            // Get the ingredient to use
            $ingredient = Ingredient::where('id', $request->id)->first();
            if(isset($ingredient)) {
                return view('components.ingredient-input', ['ingredient' => $ingredient, 'index' => $request->index])->render();
            } else {
                abort(404);
            }
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

    /**
     * Method to add a Instruction input to the Recipe Form
     */
    public function addDirection(Request $request) {
        // Check that the request is ajax
        if ($request->ajax()) {
            return view('components.direction-input', ['index' => $request->index])->render();
        // Else return a 404 not found error
        } else {
            abort(404);
        }
    }

}

// TODO Warnings if Recipe outside of Users normal taste preferences
