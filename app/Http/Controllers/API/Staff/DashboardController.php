<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    protected function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index() : JsonResponse {
        try {
            // mengambil data dari dashboard service
            $dashboardDataStaff = $this->dashboardService->dashboardStaff();

            return $this->successResponse($dashboardDataStaff,'Berhasil mengambil data dashboard Staff');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal Mengambil data dashboard staff');
        }
    }
}
