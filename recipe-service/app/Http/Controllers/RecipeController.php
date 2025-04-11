<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    // Получить все рецепты
    public function index()
    {
        return Recipe::all();
    }

    
    // Создать новый рецепт
    public function store(Request $request)
    {
        \Log::info('Request data:', $request->all());
        
        try {
            $this->validate($request, [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|array',
                'instructions' => 'required|string'
            ]);

            // Проверка существования ингредиентов
            $ingredients = $request->input('ingredients');
            $existingIngredients = \DB::table('ingredients')
                                ->whereIn('id', $ingredients)
                                ->pluck('id')
                                ->toArray();
            
            if (count($ingredients) != count($existingIngredients)) {
                return response()->json([
                    'error' => 'One or more ingredients not found'
                ], 422);
            }

            $recipe = Recipe::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'ingredients' => json_encode($request->input('ingredients')),
                'instructions' => $request->input('instructions')
            ]);

            return response()->json($recipe, 201);

        } catch (\Exception $e) {
            \Log::error('Error creating recipe: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => env('APP_DEBUG') ? $e->getTrace() : []
            ], 500);
        }
    }

    // Получить один рецепт
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        
        // Получаем данные об ингредиентах
        $ingredients = $this->getIngredientsDetails($recipe->ingredients);
        $recipe->ingredients_details = $ingredients;
        
        return $recipe;
    }

    // Обновить рецепт
    public function update(Request $request, $id)
    {
        // Проверяем существование ингредиентов
        $ingredientIds = $request->input('ingredients', []);
        $this->validateIngredients($ingredientIds);
        
        $recipe = Recipe::findOrFail($id);
        $recipe->update($request->all());
        return response()->json($recipe, 200);
    }

    // Удалить рецепт
    public function destroy($id)
    {
        Recipe::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    // Проверяем существование ингредиентов
    private function validateIngredients(array $ingredientIds)
    {
        $response = Http::get(env('INGREDIENT_SERVICE_URL') . '/api/ingredients');
        
        if (!$response->successful()) {
            throw new \Exception('Не удалось подключиться к сервису ингредиентов');
        }
        
        $existingIngredients = collect($response->json())->pluck('id')->toArray();
        
        foreach ($ingredientIds as $id) {
            if (!in_array($id, $existingIngredients)) {
                throw new \Exception("Ингредиент с ID $id не существует");
            }
        }
    }

    // Получаем детали ингредиентов
    private function getIngredientsDetails(array $ingredientIds)
    {
        if (empty($ingredientIds)) {
            return [];
        }
        
        $response = Http::get(env('INGREDIENT_SERVICE_URL') . '/api/ingredients', [
            'ids' => $ingredientIds
        ]);
        
        if (!$response->successful()) {
            return [];
        }
        
        return $response->json();
    }
}