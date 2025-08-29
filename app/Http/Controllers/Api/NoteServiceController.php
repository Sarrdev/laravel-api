<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteServiceResource;
use App\Models\ProcesVerbal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\NoteService;

class NoteServiceController extends Controller
{
    /**
     * Liste des procès-verbaux
     */
    public function index()
    {
        try {
            $noteservices = NoteService::latest()->get()->map(function ($item) {
                if ($item->fichier) {
                    // Génère une URL publique valide (sans /api/)
                    $item->fichier_url = asset('storage/' . $item->fichier);
                }
                return $item;
            });

            return response()->json([
                "status" => true,
                "data"   => $noteservices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'error'   => 'Erreur lors de la récupération',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau procès-verbal
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "titre"        => "required|string|max:255",
                "contenu"      => "required|string",
                "date_publication" => "required|date",
                "auteur"       => "nullable|string|max:255",
                "fichier"      => "nullable|file|mimes:pdf|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('fichier')) {
                $path = $request->file('fichier')->store('proces_verbaux', 'public');
                $data['fichier'] = $path;
            }

            $noteservice = NoteService::create($data);

            if ($noteservice->fichier) {
                $noteservice->fichier_url = asset('storage/' . $noteservice->fichier);
            }

            return response()->json([
                "status"  => true,
                "message" => "Procès-verbal créé avec succès",
                "data"    => $noteservice
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "error"   => "Erreur lors de la création",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un procès-verbal
     */
    public function show($id)
    {
        try {
            $noteservice = NoteService::findOrFail($id);

            if ($noteservice->fichier) {
                $noteservice->fichier_url = asset('storage/' . $noteservice->fichier);
            }

            return new NoteServiceResource($noteservice);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "error"   => "Procès-verbal non trouvé",
                "message" => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mise à jour d’un procès-verbal
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                "titre"        => "sometimes|required|string|max:255",
                "contenu"      => "sometimes|nullable|string",
                "date_publication" => "sometimes|required|date",
                "auteur"       => "sometimes|nullable|string|max:255",
                "fichier"      => "nullable|file|mimes:pdf|max:2048",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "errors" => $validator->errors()
                ], 422);
            }

            $noteservice = NoteService::findOrFail($id);
            $data = $validator->validated();

            // 🔹 Si un nouveau fichier est envoyé
            if ($request->hasFile('fichier')) {
                // Supprimer l'ancien fichier
                if ($noteservice->fichier && Storage::disk('public')->exists($noteservice->fichier)) {
                    Storage::disk('public')->delete($noteservice->fichier);
                }

                // Sauvegarder le nouveau fichier
                $path = $request->file('fichier')->store('proces_verbaux', 'public');
                $data['fichier'] = $path;
            }

            // 🔹 Mise à jour en base
            $noteservice->update($data);

            // 🔹 Recharger l'objet depuis la DB pour avoir les nouvelles valeurs
            $noteservice->refresh();

            // 🔹 Ajouter l’URL du fichier
            if ($noteservice->fichier) {
                $noteservice->fichier_url = asset('storage/' . $noteservice->fichier);
            }

            return response()->json([
                "status"  => true,
                "message" => "Note de service mis à jour avec succès",
                "data"    => $noteservice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "error"   => "Erreur lors de la mise à jour",
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
            $noteservice = NoteService::findOrFail($id);

            if ($noteservice->fichier && Storage::disk('public')->exists($noteservice->fichier)) {
                Storage::disk('public')->delete($noteservice->fichier);
            }

            $noteservice->delete();

            return response()->json([
                "status"  => true,
                "message" => "Procès-verbal supprimé avec succès"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "error"   => "Erreur lors de la suppression",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
