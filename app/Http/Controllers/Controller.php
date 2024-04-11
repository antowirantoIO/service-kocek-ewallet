<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success(
        bool $success = true,
        string $message = 'Success',
        int $code = 200,
        array | object $data = []
    ) {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function error(
        bool $success = false,
        string $message = 'Error',
        int $code = 400,
        array | object $data = []
    ) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
