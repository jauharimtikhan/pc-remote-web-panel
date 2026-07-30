<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
// use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count(); // Sesuaikan kolom status aktif
        $totalDevices = Device::count();

        // Data array buat dilempar ke chart
        $chartData = [
            'labels' => ['Total User', 'User Aktif', 'Total Device'],
            'data' => [$totalUsers, $activeUsers, $totalDevices],
        ];

        return view('pages.beranda.index', compact('totalUsers', 'activeUsers', 'totalDevices', 'chartData'));
    }
}
