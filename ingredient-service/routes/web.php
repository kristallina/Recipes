<?php

use Illuminate\Support\Facades\Router;
use App\Http\Controllers\IngredientController;

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('ingredients', 'IngredientController@index');
    $router->post('ingredients', 'IngredientController@store');
    $router->get('ingredients/{id}', 'IngredientController@show');
    $router->put('ingredients/{id}', 'IngredientController@update');
    $router->delete('ingredients/{id}', 'IngredientController@destroy');
});