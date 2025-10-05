<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\ProfileServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileServiceInterface $profileService
    ) {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $stats = $this->profileService->getUserStats($user->id);
        $completion = $this->profileService->getProfileCompletion($user->id);

        return view('admin.profile.index', compact('user', 'stats', 'completion'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$this->profileService->canEditProfile($user->id)) {
            return back()->with('error', 'Unauthorized to edit this profile.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $success = $this->profileService->updateProfile($user->id, $validated);

        if (!$success) {
            return back()->with('error', 'Failed to update profile.');
        }

        return redirect()->route('admin.profile.edit')
                        ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $success = $this->profileService->updatePassword(
            $user->id,
            $validated['current_password'],
            $validated['password']
        );

        if (!$success) {
            return back()->with('error', 'Failed to update password. Please check your current password.');
        }

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $success = $this->profileService->updateAvatar($user->id, $validated['avatar']);

        if (!$success) {
            return back()->with('error', 'Failed to update avatar.');
        }

        return back()->with('success', 'Avatar berhasil diperbarui.');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        $success = $this->profileService->deleteAvatar($user->id);

        if (!$success) {
            return back()->with('error', 'Failed to delete avatar.');
        }

        return back()->with('success', 'Avatar berhasil dihapus.');
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'theme' => ['string', 'in:light,dark,auto'],
            'language' => ['string', 'in:en,id'],
            'timezone' => ['string'],
            'notifications' => ['array'],
        ]);

        $success = $this->profileService->updatePreferences($user->id, $validated);

        if (!$success) {
            return back()->with('error', 'Failed to update preferences.');
        }

        return back()->with('success', 'Preferences berhasil diperbarui.');
    }

    /**
     * Get user activity.
     */
    public function activity(Request $request): View
    {
        $user = $request->user();
        $activities = $this->profileService->getUserActivity($user->id, 20);

        return view('admin.profile.activity', compact('user', 'activities'));
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $this->profileService->deleteProfile($user->id);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update the user's password.
     */
    public function password(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $success = $this->profileService->forceUpdatePassword(
            $request->user()->id,
            $validated['password']
        );

        if (!$success) {
            return back()->with('error', 'Password gagal diubah.');
        }

        return back()->with('success', 'Password berhasil diubah.');
    }
}
