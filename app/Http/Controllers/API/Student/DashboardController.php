<?php

namespace App\Http\Controllers\API\Student;

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


}
