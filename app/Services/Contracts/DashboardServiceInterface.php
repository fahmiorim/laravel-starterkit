<?php

namespace App\Services\Contracts;

interface DashboardServiceInterface
{
    public function getDashboardStats(): array;

    public function getReportStats(string $period = 'monthly'): array;

    public function getUpcomingSchedules(int $limit = 5): \Illuminate\Database\Eloquent\Collection;

    public function getRecentRequests(int $limit = 5): \Illuminate\Database\Eloquent\Collection;

    public function getBloodTypeDistribution(): array;

    public function getDonationTrends(int $months = 12): array;

    public function getSystemHealth(): array;

    public function getUserActivityStats(): array;

    public function getRecentHistories(int $limit = 5): \Illuminate\Database\Eloquent\Collection;

    public function getLowStock(int $threshold = 5): \Illuminate\Database\Eloquent\Collection;

    public function getRecentUsers(int $limit = 5): \Illuminate\Database\Eloquent\Collection;

    public function getUserDashboardData(int $upcomingLimit = 3, int $historyLimit = 5): array;
}
