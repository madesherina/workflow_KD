<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\PublishQueue;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PublishController extends Controller
{
    public function index(Request $request)
    {
        // Main Queue: Contents that are 'approved' or 'scheduled'
        $query = Content::with(['creator', 'approver', 'publishQueues'])
            ->whereIn('status', ['approved', 'published', 'rejected']) // Show approved for publishing
            ->where(function($q) {
                $q->where('status', 'approved')
                  ->orWhereHas('publishQueues', function($sq) {
                      $sq->whereIn('queue_status', ['scheduled', 'waiting']);
                  });
            });

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->whereHas('publishQueues', function($q) use ($request) {
                $q->where('queue_status', $request->status);
            });
        }

        $contents = $query->latest()->paginate(10);

        // Upcoming Schedule
        $upcoming = PublishQueue::with('content.creator')
            ->where('queue_status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        // Published Today
        $publishedToday = Content::where('status', 'published')
            ->whereDate('publish_date', Carbon::today())
            ->latest()
            ->get();

        return view('publish.index', compact('contents', 'upcoming', 'publishedToday'));
    }

    public function publishNow(Content $content)
    {
        $oldStatus = $content->status;
        
        $content->update([
            'status' => 'published',
            'published_by' => Auth::id(),
            'publish_date' => now()
        ]);

        // Update or Create Queue entry
        PublishQueue::updateOrCreate(
            ['content_id' => $content->id],
            ['queue_status' => 'published', 'scheduled_at' => now()]
        );

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'published',
            'note' => 'Content published immediately by publisher'
        ]);

        return redirect()->back()->with('success', 'Content published successfully.');
    }

    public function schedule(Request $request, Content $content)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now'
        ]);

        PublishQueue::updateOrCreate(
            ['content_id' => $content->id],
            [
                'scheduled_at' => $request->scheduled_at,
                'queue_status' => 'scheduled'
            ]
        );

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $content->status,
            'new_status' => $content->status,
            'note' => 'Content scheduled for publish at ' . $request->scheduled_at
        ]);

        return redirect()->back()->with('success', 'Content scheduled successfully.');
    }

    public function cancel(Content $content)
    {
        $queue = PublishQueue::where('content_id', $content->id)->first();
        if ($queue) {
            $queue->update(['queue_status' => 'cancelled']);
        }

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $content->status,
            'new_status' => $content->status,
            'note' => 'Publish schedule cancelled'
        ]);

        return redirect()->back()->with('success', 'Publish schedule cancelled.');
    }
}
