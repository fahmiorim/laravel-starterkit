<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\SettingsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsServiceInterface $settingsService
    ) {}

    /**
     * Show the application settings page.
     */
    public function index(string $group = 'general'): View
    {
        $settings = $this->settingsService->getGroupedSettings();

        return view('admin.settings.index', [
            'title' => 'Application Settings',
            'currentGroup' => $group,
            'settings' => $settings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $group = $request->input('group', 'general');

            if ($request->hasFile('company_logo')) {
                $this->settingsService->updateFileSetting('company_logo', $request->file('company_logo'));
            }

            $input = $request->except(['_token', '_method', 'group', 'company_logo']);

            $success = $this->settingsService->setSettings($input);

            if (!$success) {
                throw new \Exception('Failed to update settings');
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil diperbarui',
                'redirect' => route('admin.settings.index', ['group' => $group])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove uploaded image.
     */
    public function removeImage(Request $request): JsonResponse
    {
        $imagePath = $request->input('image_path');
        $currentPath = $this->settingsService->getSetting('company_logo');

        if ($currentPath && $imagePath === $currentPath) {
            $this->settingsService->deleteFileSetting('company_logo');

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    }

    /**
     * Get settings by group.
     */
    public function getByGroup(string $group): JsonResponse
    {
        $settings = $this->settingsService->getSettingsByGroup($group);

        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }

    /**
     * Update settings for specific group.
     */
    public function updateGroup(Request $request, string $group): JsonResponse
    {
        try {
            $settings = $request->except(['_token', '_method']);

            $success = $this->settingsService->updateSettingsByGroup($group, $settings);

            if (!$success) {
                throw new \Exception('Failed to update settings');
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
