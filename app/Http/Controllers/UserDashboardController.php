<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DashboardServiceInterface;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __construct(
        private readonly DashboardServiceInterface $dashboardService
    ) {}

    public function __invoke(): View
    {
        $dashboardData = $this->dashboardService->getUserDashboardData(3, 5);

        return view('dashboard.index', $dashboardData);
    }
}
