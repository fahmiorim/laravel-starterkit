<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show the application settings page.
     */
    public function index($group = 'general'): View
    {
        // Ambil semua setting dari database
        $settings = [
            'general' => [
                'app_name' => Setting::getValue('app_name', 'StarterKit'),
                'app_url' => Setting::getValue('app_url', url('/')),
                'timezone' => Setting::getValue('timezone', 'UTC'),
                'locale' => Setting::getValue('locale', 'en'),
            ],
            'company' => [
                'company_name' => Setting::getValue('company_name', 'My Company'),
                'company_address' => Setting::getValue('company_address', ''),
                'company_phone' => Setting::getValue('company_phone', ''),
                'company_email' => Setting::getValue('company_email', ''),
                'company_logo' => Setting::getValue('company_logo', ''),
            ],
            'social' => [
                'facebook_url' => Setting::getValue('facebook_url', ''),
                'twitter_url' => Setting::getValue('twitter_url', ''),
                'instagram_url' => Setting::getValue('instagram_url', ''),
                'linkedin_url' => Setting::getValue('linkedin_url', ''),
            ]
        ];

        return view('admin.settings.index', [
            'title' => 'Application Settings',
            'currentGroup' => $group,
            'settings' => $settings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        try {
            $group = $request->input('group', 'general');
            
            // Handle file upload
            if ($request->hasFile('company_logo')) {
                $oldLogo = Setting::getValue('company_logo');
                $path = $request->file('company_logo')->store('settings', 'public');
                
                // Simpan path baru
                Setting::setValue('company_logo', $path);
                
                // Hapus logo lama jika ada
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            // Update settings dari request
            $input = $request->except(['_token', '_method', 'group', 'company_logo']);
            
            foreach ($input as $key => $value) {
                if (!is_null($value)) {
                    Setting::setValue($key, $value);
                }
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan berhasil diperbarui',
                    'redirect' => route('admin.settings.index', ['group' => $group])
                ]);
            }
            
            return redirect()
                ->route('admin.settings.index', ['group' => $group])
                ->with('success', 'Pengaturan berhasil diperbarui');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove uploaded image.
     */
    public function removeImage(Request $request)
    {
        $imagePath = $request->input('image_path');
        
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            // Delete the image file
            Storage::disk('public')->delete($imagePath);
            
            // Remove from settings
            $settingsPath = config_path('app_settings.php');
            if (File::exists($settingsPath)) {
                $settings = require $settingsPath;
                
                // Only proceed if the logo exists in settings
                if (isset($settings['company_logo'])) {
                    unset($settings['company_logo']);
                    
                    // Generate the new config file content
                    $content = "<?php\n\nreturn " . $this->varExport($settings) . ";\n";
                    File::put($settingsPath, $content);
                }
            }
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    }

    /**
     * Helper function to export array as PHP code.
     */
    private function varExport($var, $indent = '    ')
    {
        if (is_array($var)) {
            if (empty($var)) {
                return '[]';
            }
            
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            
            foreach ($var as $key => $value) {
                $r[] = "{$indent}" . ($indexed ? '' : $this->varExport($key) . ' => ') . $this->varExport($value, "$indent    ");
            }
            
            return "[\n" . implode(",\n", $r) . "\n" . substr($indent, 0, -4) . "]";
        }
        
        if (is_string($var)) {
            return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], $var) . "'";
        }
        
        if (is_bool($var)) {
            return $var ? 'true' : 'false';
        }
        
        if (is_null($var)) {
            return 'null';
        }
        
        if (is_numeric($var)) {
            return (string) $var;
        }
        
        if (is_object($var) && method_exists($var, '__toString')) {
            return "'" . (string) $var . "'";
        }
        
        return 'null'; // Fallback for any other type
    }
}
