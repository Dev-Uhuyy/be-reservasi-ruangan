<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\Request;


class HistoryController extends Controller
{
    public function __construct(protected VerificationService $verificationService)
    {
    }

    public function index(Request $request)
    {
        $histories = $this->verificationService->getVerificationHistoryForStaff($request);
        return response()->json($histories);
    }
}
