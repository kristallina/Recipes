<?php

use Illuminate\Support\Facades\Router;
use App\Http\Controllers\RecipeController;

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('recipes', 'RecipeController@index');
    $router->post('recipes', 'RecipeController@store');
    $router->get('recipes/{id}', 'RecipeController@show');
    $router->put('recipes/{id}', 'RecipeController@update');
    $router->delete('recipes/{id}', 'RecipeController@destroy');
});