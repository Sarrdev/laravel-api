<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Si un ID est présent dans la route, on vérifie qu'il correspond à l'utilisateur
        $userId = $request->route('id');

        if ($userId && $user->id != $userId && $user->role !== 'admin') {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        // Sinon, on laisse passer (ex: GET convocations)
        return $next($request);
    }
}
