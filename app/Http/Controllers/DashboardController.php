<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user->isPlatformUser()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard.index');
    }
}
