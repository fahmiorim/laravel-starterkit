<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show the application settings page.
     */
    public function index(): View
    {
        return view('admin.settings.index', [
            'title' => 'Application Settings',
            'settings' => [
                'app_name' => config('app.name'),
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug') ? 'true' : 'false',
                'app_url' => config('app.url'),
            ]
        ]);
    }
}
