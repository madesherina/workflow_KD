<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;

class VerifierDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'waiting_review' => Content::where('status', 'review')->count(),
            'approved' => Content::where('status', 'approved')->count(),
            'rejected' => Content::where('status', 'rejected')->count(),
        ];

        $recentActivity = ContentHistory::with(['content', 'actor'])
            ->where('action_by', Auth::id())
            ->latest()
            ->take(8)
            ->get();

        $reviewQueue = Content::where('status', 'review')
            ->with('creator')
            ->latest()
            ->take(5)
            ->get();

        return view('verifier.dashboard', compact('stats', 'recentActivity', 'reviewQueue'));
    }
}
