<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $totalStaffs = User::where('role', 'staff')->count();
        $totalUsers = User::where('role', 'user')->count();
        return view('admin.dashboard', compact('totalAdmins', 'totalStaffs', 'totalUsers'));
    }
}
