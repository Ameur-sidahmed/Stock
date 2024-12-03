<?php

namespace App\Http\Controllers;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::select('id', 'nom', 'created_at', 'updated_at')->get();
        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Erreur'
            ], 404);
        }

        $categoriesData = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'nom' => $category->nom,
                'timestamps' => $category->created_at
            ];
        });

        return response()->json([
            'data' => $categoriesData,
            'message' => 'success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Création de la catégorie avec les données du front-end
        $categorie = Categorie::create([
            'nom' => $request->input('nom'), // Récupérer 'nom' depuis la requête
        ]);

        return response()->json([
            'data' => $categorie,
            'message' => 'Categorie created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = Categorie::find($id);

            if (!$categorie) {
                return response()->json([
                    'message' => 'Erreur catégorie non trouvée',
                ], 404);
            }

            $categorieData = [
                'id' => $categorie->id,
                'nom' => $categorie->nom,
                'timestamps' => $categorie->created_at
            ];

            return response()->json([
                'data' => $categorieData,
                'message' => 'Categorie retrieved successfully'
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categorie = Categorie::find($id);

            if (!$categorie) {
                return response()->json(['message' => 'Categorie not found'], 404);
            }

            $request->validate([
                'nom' => 'required|string|max:255',
            ]);

            $categorie->update([
                'nom' => $request->input('nom'),
            ]);

            return response()->json([
                'data' => $categorie,
                'message' => 'Categorie updated successfully',
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categorie = Categorie::find($id);

            if (!$categorie) {
                return response()->json(['message' => 'Categorie not found'], 404);
            }

            $categorie->delete();

            return response()->json(['message' => 'Categorie deleted successfully'], 200);
    }
}
