<?php

namespace App\Helpers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptions
{
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
    ];

    public static function handleAuthenticationException(AuthenticationException $e, Request $request)
    {
        return response()->json([
            'errors' => [
                'type' => basename(get_class($e)),
                'status' => 401,
                'message' => $e->getMessage(),
            ],
        ]);
    }

    public static function handleValidationException(ValidationException $e, Request $request)
    {
        $errors = [];
        $type = basename(get_class($e));
        foreach ($e->errors() as $value) {
            foreach ($value as $message) {
                $errors[] = [
                    'type' => $type,
                    'status' => 422,
                    'message' => $message,
                ];
            }
        }

        return response()->json([
            'errors' => $errors,
        ]);
    }

    public static function handleNotFoundException(ModelNotFoundException|NotFoundHttpException $e, Request $request)
    {
        return response()->json([
            'errors' => [
                'type' => basename(get_class($e)),
                'status' => 404,
                'message' => $e->getMessage(),
            ],
        ]);
    }

    public static function error($message, $statusCode)
    {
        return response()->json([
            'errors' => [
                'type' => '',
                'status' => $statusCode,
                'message' => $message,
            ],
        ]);
    }
}
