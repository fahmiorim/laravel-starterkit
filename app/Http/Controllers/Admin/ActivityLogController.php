<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(): View
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(25);

        return view('admin.activity-log.index', [
            'title' => 'Activity Log',
            'activities' => $activities
        ]);
    }
}
