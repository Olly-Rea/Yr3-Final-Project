<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Routes that can be used by guests or Auth users who have setup an account
Route::middleware(['guestorauthsetup'])->group(function () {
    // Welcome route
    Route::get('/', 'StartController@start')->name('welcome');

    // Main search bar results route
    Route::get('/Search', 'SearchController@search')->name('search');

    // Main feed / 'Lucky Dip' routes
    Route::get('/IdeasBoard', 'RecipeController@index')->name('feed');
    Route::get('/LuckyDip', 'RecipeController@surprise')->name('lucky_dip');

    // View Recipe route
    Route::get('/Recipe/{recipe}', 'RecipeController@show')->name('recipe');
    // View Ingredient Data route
    Route::get('/Ingredient/{ingredient}', 'IngredientController@show')->name('ingredient');
    // View Profile routes
    Route::get('/Profile/{user}', 'ProfileController@show')->name('profile');
});

// Routes that require an authorised account, however may not yet be setup
Route::middleware(['auth'])->group(function () {
    // Individual item search routes
    Route::get('/Search/Ingredient', 'SearchController@ingredient')->name('search.ingredient');
    // Route::get('/Search/Recipe', 'SearchController@recipe')->name('search.recipe');
    Route::get('/Search/Allergen', 'SearchController@allergen')->name('search.allergen');

    // Routes to add / remove fridge ingredients from a Users account
    Route::get('/Fridge/Add', 'FridgeController@addTo')->name('fridge.add');
    Route::get('/Fridge/Remove', 'FridgeController@removeFrom')->name('fridge.remove');
    // Routes to add / remove allergens from a Users account
    Route::get('/Allergen/Add', 'AllergenController@addTo')->name('allergen.add');
    Route::get('/Allergen/Remove', 'AllergenController@removeFrom')->name('allergen.remove');
});

// Routes that can only be used by Auth users who have setup an account
Route::middleware(['authsetup'])->group(function () {
    // Route to display on first creation of a User
    Route::get('/GetStarted', 'ProfileController@getStarted')->name('auth.setup');

    // Routes to display, edit and delete the Auth user profile
    Route::get('/Me', 'ProfileController@me')->name('me');
    Route::post('/Me/update', 'ProfileController@update')->name('me.update');
    Route::post('/Me/delete', 'ProfileController@delete')->name('me.delete');

    // Routes to create and edit (and persist changes to) recipes
    Route::get('/recipe/create', 'RecipeController@create')->name('recipe.create');
    Route::post('/recipe/create', 'RecipeController@save')->name('recipe.save');
    Route::get('/recipe/edit/{recipe}', 'RecipeController@edit')->name('recipe.edit');
    Route::post('/recipe/edit/{recipe}', 'RecipeController@update')->name('recipe.update');

    // View results created by the AI chef
    Route::get('/TheAiChef', 'RecipeController@showAI')->name('ai_chef');
});


