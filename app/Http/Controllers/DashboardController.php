<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalReports = Report::where('reporter_id', $user->id)->count();
        $totalPoints = $user->total_points ?? 0;

        $recentReports = Report::where('reporter_id', $user->id)
            ->latest('reported_at')
            ->take(5)
            ->get();

        $topUsers = User::orderByDesc('total_points')
            ->take(10)
            ->get(['id', 'name', 'total_points']);

        return view('dashboard.index', compact(
            'totalReports',
            'totalPoints',
            'recentReports',
            'topUsers'
        ));
    }
}
