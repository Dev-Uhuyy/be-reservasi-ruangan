<?php

namespace App\Http\Controllers\API\Student;

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

    public function index(): JsonResponse
    {
        try {
            // get data from dashboard service
            $dashboardDataStudent = $this->dashboardService->dashboardStudent();

            return $this->successResponse($dashboardDataStudent, 'Berhasil Mengambil Data Dashboard student');
        } catch (\Throwable $e) {

            // throw exception eror
            return $this->exceptionError($e, 'Gagal Mengambil data dashboard Student');
        }
    }
}
