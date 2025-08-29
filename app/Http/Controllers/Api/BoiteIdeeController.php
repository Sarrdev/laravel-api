<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BoiteIdee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoiteIdeeController extends Controller
{
      // Liste des idées (admin)
    public function index()
    {
        try {
            $idees = BoiteIdee::orderBy('created_at', 'desc')->get();

            return response()->json([
                'message' => 'Liste des idées récupérée',
                'data' => $idees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // Soumettre une idée
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idee' => 'required|string|max:2000',
                'nom_complet' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors()
                ], 422);
            }

            $boiteIdee = BoiteIdee::create([
                'nom_complet' => $request->nom_complet,
                'idee' => $request->idee,
                'statut' => 'soumis',
            ]);

            return response()->json([
                'message' => 'Idée soumise avec succès',
                'data' => $boiteIdee
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la soumission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $idee = BoiteIdee::findOrFail($id);

            return response()->json([
                'message' => 'Idée récupérée',
                'data' => $idee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idee' => 'required|string|max:2000',
                'nom_complet' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors()
                ], 422);
            }

            $idee = BoiteIdee::findOrFail($id);
            $idee->update([
                'nom_complet' => $request->nom_complet,
                'idee' => $request->idee,
            ]);

            return response()->json([
                'message' => 'Idée mise à jour avec succès',
                'data' => $idee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $idee = BoiteIdee::findOrFail($id);
            $idee->delete();

            return response()->json([
                'message' => 'Idée supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Changer le statut d’une idée (admin)
    public function updateStatut($id)
    {
        try {
            $idee = BoiteIdee::findOrFail($id);
            $idee->update(['statut' => 'recu']);

            return response()->json([
                'message' => 'Statut mis à jour',
                'data' => $idee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
