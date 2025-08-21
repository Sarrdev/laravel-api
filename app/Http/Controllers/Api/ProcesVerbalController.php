<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProcesVerbal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProcesVerbalController extends Controller
{
    /**
     * Afficher la liste des procès-verbaux
     */
    public function index()
    {
        try {
            $procesVerbaux = ProcesVerbal::all();

            return response()->json([
                "status" => true,
                "data" => $procesVerbaux
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => "Erreur lors de la récupération des procès-verbaux",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistrer un nouveau procès-verbal
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "titre" => "required|string|max:255",
                "contenu" => "required|string",
                "date_reunion" => "required|date",
                "auteur" => "nullable|string|max:255",
                "fichier" => "nullable|file|mimes:pdf|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Gestion de l'upload du fichier si présent
            if ($request->hasFile('fichier')) {
                $path = $request->file('fichier')->store('proces_verbaux', 'public');
                $data['fichier'] = $path;
            }

            $procesVerbal = ProcesVerbal::create($data);

            return response()->json([
                "status" => true,
                "message" => "Procès-verbal créé avec succès",
                "data" => $procesVerbal
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => "Erreur lors de la création du procès-verbal",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un procès-verbal spécifique
     */
    public function show($id)
    {
        try {
            $procesVerbal = ProcesVerbal::findOrFail($id);

            return response()->json([
                "status" => true,
                "data" => $procesVerbal
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => "Procès-verbal non trouvé",
                "message" => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mettre à jour un procès-verbal
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                "titre" => "sometimes|required|string|max:255",
                "contenu" => "sometimes|nullable|string",
                "date_reunion" => "sometimes|required|date",
                "auteur" => "sometimes|nullable|string|max:255",
                "fichier" => "nullable|file|mimes:pdf|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $procesVerbal = ProcesVerbal::findOrFail($id);
            $data = $validator->validated();

            // Gestion du fichier si on met à jour
            if ($request->hasFile('fichier')) {
                // Supprimer l’ancien fichier si existe
                if ($procesVerbal->fichier && Storage::disk('public')->exists($procesVerbal->fichier)) {
                    Storage::disk('public')->delete($procesVerbal->fichier);
                }
                $path = $request->file('fichier')->store('proces_verbaux', 'public');
                $data['fichier'] = $path;
            }

            $procesVerbal->update($data);

            return response()->json([
                "status" => true,
                "message" => "Procès-verbal mis à jour avec succès",
                "data" => $procesVerbal
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => "Erreur lors de la mise à jour du procès-verbal",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un procès-verbal
     */
    public function destroy($id)
    {
        try {
            $procesVerbal = ProcesVerbal::findOrFail($id);

            // Supprimer aussi le fichier associé si existe
            if ($procesVerbal->fichier && Storage::disk('public')->exists($procesVerbal->fichier)) {
                Storage::disk('public')->delete($procesVerbal->fichier);
            }

            $procesVerbal->delete();

            return response()->json([
                "status" => true,
                "message" => "Procès-verbal supprimé avec succès"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => "Erreur lors de la suppression du procès-verbal",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
