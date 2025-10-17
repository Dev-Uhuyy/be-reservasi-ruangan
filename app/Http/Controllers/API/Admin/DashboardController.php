<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    protected function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                return $this->dashboardService->dashboardAdmin();
            case 'staff':
                return $this->dashboardService->dashboardStaff();
            case 'student':
                return $this->dashboardService->dashboardStudent();
            default:
                return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
