<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\PublishQueue;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PublisherDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'ready_to_publish' => Content::where('status', 'approved')->count(),
            'scheduled' => PublishQueue::where('queue_status', 'scheduled')
                ->where('scheduled_at', '>', now())
                ->count(),
            'published_today' => Content::where('status', 'published')
                ->whereDate('publish_date', Carbon::today())
                ->count(),
        ];

        $recentActivity = ContentHistory::with(['content', 'actor'])
            ->whereIn('new_status', ['published', 'scheduled'])
            ->latest()
            ->take(5)
            ->get();

        return view('publisher.dashboard', compact('stats', 'recentActivity'));
    }

    public function queue()
    {
        $contents = Content::with(['creator', 'approver', 'publishQueues'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('publisher.queue', compact('contents'));
    }

    public function scheduled()
    {
        $queues = PublishQueue::with(['content.creator', 'content.approver'])
            ->where('queue_status', 'scheduled')
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10);

        return view('publisher.scheduled', compact('queues'));
    }

    public function published()
    {
        $contents = Content::with(['creator', 'approver', 'publisher'])
            ->where('status', 'published')
            ->latest('publish_date')
            ->paginate(10);

        return view('publisher.published', compact('contents'));
    }

    public function logs()
    {
        $logs = ContentHistory::with(['content', 'actor'])
            ->latest()
            ->paginate(20);

        return view('publisher.logs', compact('logs'));
    }
}
