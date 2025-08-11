<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatriculeAutorise;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Inscription d'un utilisateur
    public function register(Request $request)
    {
        $plainPassword = $request->password;

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'fonction' => 'nullable|string',
            'telephone' => 'required|string',
            'email' => 'required|email|unique:users',
            'matricule' => 'nullable|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
        ]);

        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'fonction' => $request->fonction,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'matricule' => $request->matricule,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        $user->notify(new UserCreatedNotification($user->email, $plainPassword));

        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user
        ], 201);
    }
    // Connexion d'un utilisateur
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Création du token
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'message' => 'Connexion réussie',
                'role' => $user->role, // tu peux retourner le rôle pour que le frontend redirige
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Les identifiants sont incorrects.',
        ], 401);
    }


    // Déconnexion d'un utilisateur
    public function logout(Request $request)
    {
        $request->user()->tokens->each(fn($token) => $token->delete());

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }

    // Profil de l'utilisateur connecté
    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Utilisateur non authentifié",
                "data" => null
            ], 401);
        }

        return response()->json([
            "status" => true,
            "message" => "Profil de l'utilisateur",
            "data" => $user
        ], 200);
    }


    // Utilisateur connecté (alternative)
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des utilisateurs'], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation stricte
        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'fonction' => 'nullable|string|max:255',
            'role' => 'sometimes|in:user,admin',
            'password' => 'nullable|min:6',
        ]);

        // Gestion du mot de passe s'il est envoyé
        if (isset($validatedData['password'])) {
            $validatedData['password'] = \Illuminate\Support\Facades\Hash::make($validatedData['password']);
        }

        // Mise à jour sécurisée
        $user->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Utilisateur mis à jour avec succès',
            'data' => $user
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Utilisateur supprimé avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'utilisateur'], 500);
        }
    }
    
}
