<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class SuperAdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('super-admin.dashboard');
    }
}
