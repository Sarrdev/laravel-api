<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Convocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConvocationController extends Controller
{
    // ✅ Liste des convocations
    public function index()
    {
        try {
            $convocations = Convocation::latest()->get();
            return response()->json([
                "status" => true,
                "data" => $convocations
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des convocations'], 500);
        }
    }

    // ✅ Créer une convocation
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_reunion' => 'required|date',
            'lieu' => 'required|string|max:255',
            'statut' => 'in:planifiée,reportée,annulée',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $convocation = Convocation::create($validator->validated());

        return response()->json([
            'message' => 'Convocation créée avec succès',
            'data' => $convocation
        ], 201);
    }

    // ✅ Afficher une convocation
    public function show($id)
    {
        $convocation = Convocation::findOrFail($id);


        return response()->json([
            'status' => true,
            'data' => $convocation
        ]);
    }

    // ✅ Modifier une convocation
    public function update(Request $request, $id)
    {
        $convocation = Convocation::find($id);

        if (!$convocation) {
            return response()->json(['message' => 'Convocation introuvable'], 404);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'date_reunion' => 'sometimes|date',
            'lieu' => 'sometimes|string|max:255',
            'statut' => 'in:planifiée,reportée,annulée',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $convocation->update($validator->validated());

        return response()->json([
            'message' => 'Convocation mise à jour avec succès',
            'data' => $convocation
        ]);
    }

    // ✅ Supprimer une convocation
    public function destroy($id)
    {
        $convocation = Convocation::find($id);

        if (!$convocation) {
            return response()->json(['message' => 'Convocation introuvable'], 404);
        }

        $convocation->delete();

        return response()->json(['message' => 'Convocation supprimée avec succès']);
    }
}
