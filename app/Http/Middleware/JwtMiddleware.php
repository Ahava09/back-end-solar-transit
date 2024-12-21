<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parsing\TokenParser;
use Lcobucci\JWT\UnencryptedToken;
use Exception;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Récupérer le token depuis les en-têtes de la requête
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $config = Configuration::forSymmetricSigner(
                new \Lcobucci\JWT\Signer\Hmac\Sha256(),
                \Lcobucci\JWT\Signer\Key\InMemory::plainText(env('JWT_SECRET'))
            );

            // Parser le token
            $token = $config->parser()->parse($token);

            // Valider le token
            if ($token->isExpired()) {
                return response()->json(['message' => 'Token has expired'], 401);
            }

            // Récupérer les informations du token (par exemple, user_id)
            $userId = $token->claims()->get('user_id');

            // Ajouter l'utilisateur à la requête (vous pouvez l'utiliser plus tard)
            $request->merge(['user_id' => $userId]);

        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
