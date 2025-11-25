<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminDashboardController extends PublicDashboardController
{
    /**
     * Override landing page for admin
     */
    public function landing()
    {
        // Reuse logic from parent but render admin view
        $viewData = $this->getLandingData();
        return view('admin.landing', $viewData);
    }

    /**
     * Override data page for admin
     */
    public function data(Request $request)
    {
        $viewData = $this->getDataViewData($request);
        return view('admin.data', $viewData);
    }

    /**
     * Override charts page for admin
     */
    public function charts(Request $request)
    {
        $viewData = $this->getChartsViewData($request);
        return view('admin.charts', $viewData);
    }

    /**
     * Override compare page for admin
     */
    public function compare(Request $request)
    {
        $viewData = $this->getCompareViewData($request);
        return view('admin.compare', $viewData);
    }

    /**
     * Admin Import Page
     */
    public function import()
    {
        return view('admin.import', [
            'title' => 'Import Data',
        ]);
    }

    /**
     * Admin Download Logs Page
     */
    public function downloadLogs()
    {
        $logs = DownloadLog::latest()->paginate(20);
        return view('admin.download-logs', [
            'title' => 'Log Download User',
            'logs' => $logs,
        ]);
    }

    /**
     * Admin Account Page
     */
    public function account()
    {
        return view('admin.account', [
            'title' => 'Pengaturan Akun',
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update Admin Account
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Admin Data Fullscreen
     */
    public function fullscreen(Request $request)
    {
        $viewData = $this->getFullscreenViewData($request);
        return view('admin.data-fullscreen', $viewData);
    }

    /**
     * Admin Charts Fullscreen
     */
    public function chartsFullscreen(Request $request)
    {
        $viewData = $this->getChartsFullscreenViewData($request);
        return view('admin.charts-fullscreen', $viewData);
    }

    /**
     * Admin Compare Fullscreen
     */
    public function compareFullscreen(Request $request)
    {
        $viewData = $this->getCompareFullscreenViewData($request);
        return view('admin.compare-fullscreen', $viewData);
    }
}
