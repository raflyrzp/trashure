<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('total_points')
            ->orderBy('name')
            ->paginate(20, ['id', 'name', 'total_points']);

        return view('leaderboard.index', compact('users'));
    }
}
