<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Получить все ингредиенты
    public function index()
    {
        return Ingredient::all();
    }

    // Создать новый ингредиент
    public function store(Request $request)
    {
        $ingredient = Ingredient::create($request->all());
        return response()->json($ingredient, 201);
    }

    // Получить один ингредиент
    public function show($id)
    {
        return Ingredient::findOrFail($id);
    }

    // Обновить ингредиент
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->all());
        return response()->json($ingredient, 200);
    }

    // Удалить ингредиент
    public function destroy($id)
    {
        Ingredient::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}