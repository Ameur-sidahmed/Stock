<?php

namespace App\Http\Controllers;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = Produit::select('id','categorie_id','nom','description','prix_vente','prix_achat','quantite','reference','image')->get();
        if ($produits->isEmpty()) {
            return response()->json([
                'message' => 'Erreur'
            ], 404);
        }

        $produitsData = $produits->map(function($produit) {
            return [
                'id' => $produit->id,
                'categorie_id' => $produit->categorie_id,
                'nom' => $produit->nom,
                'description' => $produit->description,
                'prix_vente' => $produit->prix_vente,
                'prix_achat' => $produit->prix_achat,
                'quantite' => $produit->quantite,
                'reference' => $produit->reference,
                'image' => $produit->image,
                'timestamps' => $produit->created_at
            ];
        });

        return response()->json([
            'data' => $produitsData,
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
            'categorie_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:0',
            'reference' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'timestamps' => 'required|date',
        ]);

        $produit = Produit::create([
            'nom' => $request->input('nom'),
            'categorie_id' => $request->input('categorie_id'),
            'description' => $request->input('description'),
            'prix_vente' => $request->input('prix_vente'),
            'prix_achat' => $request->input('prix_achat'),
            'quantite' => $request->input('quantite'),
            'reference' => $request->input('reference'),
            'image' => $request->input('image'),
        ]);

        return response()->json([
            'data' => $produit,
            'message' => 'Categorie created successfully',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produit = Produit::find($id);

            if (!$produit) {
                return response()->json([
                    'message' => 'Erreur produit non trouvée',
                ], 404);
            }

            $produitData = [
                'id' => $produit->id,
                'categorie_id' => $produit->categorie_id,
                'nom' => $produit->nom,
                'description' => $produit->description,
                'prix_vente' => $produit->prix_vente,
                'prix_achat' => $produit->prix_achat,
                'quantite' => $produit->quantite,
                'reference' => $produit->reference,
                'image' => $produit->image,
                'timestamps' => $produit->created_at
            ];

            return response()->json([
                'data' => $produitData,
                'message' => 'produit retrieved successfully'
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'produit not found'], 404);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'categorie_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'prix_vente' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:0',
            'reference' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'timestamps' => 'required|date',
        ]);

        $produit ->update([
            'nom' => $request->input('nom'),
            'categorie_id' => $request->input('categorie_id'),
            'description' => $request->input('description'),
            'prix_vente' => $request->input('prix_vente'),
            'prix_achat' => $request->input('prix_achat'),
            'quantite' => $request->input('quantite'),
            'reference' => $request->input('reference'),
            'image' => $request->input('image'),
        ]);

        return response()->json([
            'data' => $produit,
            'message' => 'produit updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = Produit::find($id);

            if (!$produit) {
                return response()->json(['message' => 'produit not found'], 404);
            }

            $produit->delete();

            return response()->json(['message' => 'produit deleted successfully'], 200);
    }
}
