<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationAlreadyProcessedException extends Exception
{
    /**
     * Render exception ke dalam HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage() ?: 'Reservation already processed.',
        ], 400); 
    }
}
