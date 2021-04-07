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

// Recipe routes
Route::get('/', 'StartController@show')->name('welcome');

Route::get('/IdeasBoard', 'RecipeController@index')->name('feed');
Route::get('/Recipe/{recipe}', 'RecipeController@show')->name('recipe');
Route::get('/LuckyDip', 'RecipeController@random')->name('lucky_dip');

// Ingredient routes
Route::get('/Ingredient/{ingredient}', 'IngredientController@show')->name('ingredient');

// Profile routes
Route::get('/Profile/{profile}', 'ProfileController@show')->name('profile');
Route::get('/Me', 'ProfileController@me')->name('me');

Route::get('/TheAiChef', 'RecipeController@showAI')->name('ai_chef');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
