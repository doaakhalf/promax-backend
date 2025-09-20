<?php

namespace App\Http\Traits;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\JsonResponse;

/**
 * Trait HandlesTokenAuthentication
 * 
 * This trait provides methods for handling token-based authentication.
 * It can be used in any controller that needs to authenticate users via bearer tokens.
 */
trait HandlesTokenAuthentication
{
    /**
     * Get the authenticated user from the bearer token
     *
     * @param \Illuminate\Http\Request $request
     * @return array Contains 'success' status, 'user' object if successful, and 'error' message if failed
     */
    protected function getAuthenticatedUser($request): array
    {
        $token = $request->bearerToken();

        if (!$token) {
            return [
                'success' => false,
                'error' => [
                    'message' => 'Authorization token not found in header',
                    'code' => 401
                ]
            ];
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return [
                'success' => false,
                'error' => [
                    'message' => 'Invalid token',
                    'code' => 401
                ]
            ];
        }

        return [
            'success' => true,
            'user' => $accessToken->tokenable
        ];
    }

    /**
     * Check if the user has the required role(s)
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @param string|array $roles
     * @return bool
     */
    protected function userHasRole($user, $roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        return in_array($user->role->name, $roles);
    }

    /**
     * Get an error response for unauthorized access
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized', int $statusCode = 403): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }
}
