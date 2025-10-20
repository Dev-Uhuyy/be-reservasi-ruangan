<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    protected $dashboardService;

    protected function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        try {
            // get dashboard
            $dashboardDataAdmin = $this->dashboardService->dashboardAdmin();

            // return success response
            return $this->successResponse($dashboardDataAdmin, 'Data admin dashboard berhasil di ambil');
        } catch (Throwable $e) {

            // return exception eror
            return $this->exceptionError($e, 'Failed to retrieve admin dashboard data');
        }
    }
}
