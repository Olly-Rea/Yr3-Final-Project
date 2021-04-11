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

// Welcome route
Route::get('/', 'StartController@start')->name('welcome');

// Main feed / 'Lucky Dip' routes
Route::get('/IdeasBoard', 'RecipeController@index')->name('feed');
Route::get('/LuckyDip', 'RecipeController@surprise')->name('lucky_dip');

// View Recipe route
Route::get('/Recipe/{recipe}', 'RecipeController@show')->name('recipe');
// View Ingredient Data route
Route::get('/Ingredient/{ingredient}', 'IngredientController@show')->name('ingredient');
// View Profile routes
Route::get('/Profile/{user}', 'ProfileController@show')->name('profile');
// Get Search results route
Route::get('/Search', 'SearchController@search')->name('search');

// Routes that can only be used by Auth users
Route::middleware(['auth:sanctum'])->group(function () {
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

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
