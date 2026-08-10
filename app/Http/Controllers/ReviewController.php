<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Content::with('creator')->where('status', 'review');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('content_type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contents = $query->latest()->paginate(10);
        return view('reviews.index', compact('contents'));
    }

    public function approve(Content $content)
    {
        $oldStatus = $content->status;
        $content->update([
            'status' => 'approved',
            'approved_by' => Auth::id()
        ]);

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'approved',
            'note' => 'Content approved for publishing'
        ]);

        return redirect()->route('reviews.index')->with('success', 'Content approved successfully.');
    }

    public function reject(Request $request, Content $content)
    {
        $request->validate([
            'rejection_note' => 'required|string|max:500'
        ]);

        $oldStatus = $content->status;
        $content->update([
            'status' => 'rejected',
            'rejection_note' => $request->rejection_note
        ]);

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => 'rejected',
            'note' => $request->rejection_note
        ]);

        return redirect()->route('reviews.index')->with('success', 'Content rejected and sent back to creator.');
    }

    public function approved()
    {
        $contents = Content::with('creator')
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);
        return view('reviews.approved', compact('contents'));
    }

    public function rejected()
    {
        $contents = Content::with('creator')
            ->where('status', 'rejected')
            ->latest()
            ->paginate(10);
        return view('reviews.rejected', compact('contents'));
    }
}
