<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Content;
use App\Models\ContentHistory;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'draft' => Content::where('status', 'draft')->count(),
            'review' => Content::where('status', 'review')->count(),
            'approved' => Content::where('status', 'approved')->count(),
            'published' => Content::where('status', 'published')->count(),
        ];

        $recentActivities = ContentHistory::with(['content', 'actor'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'recentActivities'));
    }
}
