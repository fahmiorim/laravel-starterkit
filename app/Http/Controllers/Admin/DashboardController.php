<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\DashboardServiceInterface;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardServiceInterface $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $stats = $this->dashboardService->getDashboardStats();
        $upcomingSchedules = $this->dashboardService->getUpcomingSchedules(5);
        $recentRequests = $this->dashboardService->getRecentRequests(5);
        $lowStocks = $this->dashboardService->getLowStock(5);
        $recentHistories = $this->dashboardService->getRecentHistories(5);
        $recentUsers = $this->dashboardService->getRecentUsers(5);

        return view('admin.dashboard.index', compact(
            'stats',
            'upcomingSchedules',
            'recentRequests',
            'lowStocks',
            'recentHistories',
            'recentUsers'
        ));
    }
}
