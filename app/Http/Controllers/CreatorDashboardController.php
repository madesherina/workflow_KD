<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CreatorDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_draft' => Content::where('created_by', $userId)->where('status', 'draft')->count(),
            'total_waiting' => Content::where('created_by', $userId)->where('status', 'review')->count(),
            'total_rejected' => Content::where('created_by', $userId)->where('status', 'rejected')->count(),
            'total_published' => Content::where('created_by', $userId)->where('status', 'published')->count(),
        ];

        $recentActivities = ContentHistory::whereHas('content', function($q) use ($userId) {
                $q->where('created_by', $userId);
            })
            ->with(['content', 'actor'])
            ->latest()
            ->take(5)
            ->get();

        return view('creator.dashboard', compact('stats', 'recentActivities'));
    }

    public function myContent(Request $request)
    {
        $userId = Auth::id();
        $query = Content::where('created_by', $userId);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contents = $query->latest()->paginate(10);

        return view('creator.my_content', compact('contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:image,video,mixed',
            'thumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov|max:51200',
            'copywriting_file' => 'nullable|mimes:pdf,docx|max:10240',
            'action' => 'required|in:save_draft,send_review',
        ]);

        $data = $request->only(['title', 'description', 'content_type', 'content']);
        $data['status'] = ($request->action == 'send_review') ? 'review' : 'draft';
        $data['created_by'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('content', 'public');
        }
        
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('content', 'public');
            }
            $data['images'] = $imagePaths;
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file')->store('content', 'public');
        }
        if ($request->hasFile('copywriting_file')) {
            $data['copywriting_file'] = $request->file('copywriting_file')->store('content', 'public');
        }

        $content = Content::create($data);

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => null,
            'new_status' => $data['status'],
            'note' => ($request->action == 'send_review') ? 'Content created and submitted to review' : 'Content created as draft',
        ]);

        return redirect()->route('creator.contents')->with('success', 'Content saved successfully.');
    }

    public function edit(Content $content)
    {
        if ($content->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action. You do not own this content.');
        }

        return view('creator.edit', compact('content'));
    }

    public function update(Request $request, Content $content)
    {
        if ($content->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action. You do not own this content.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:image,video,mixed',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov|max:51200',
            'copywriting_file' => 'nullable|mimes:pdf,docx|max:10240',
            'action' => 'required|in:save_draft,send_review',
        ]);

        $data = $request->only(['title', 'description', 'content_type', 'content']);
        
        $oldStatus = $content->status;
        $newStatus = ($request->action == 'send_review') ? 'review' : 'draft';
        $data['status'] = $newStatus;

        if ($request->hasFile('thumbnail')) {
            if ($content->thumbnail) Storage::disk('public')->delete($content->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('content', 'public');
        }
        
        if ($request->hasFile('images')) {
            if ($content->images && is_array($content->images)) {
                foreach ($content->images as $oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('content', 'public');
            }
            $data['images'] = $imagePaths;
        }

        if ($request->hasFile('video_file')) {
            if ($content->video_file) Storage::disk('public')->delete($content->video_file);
            $data['video_file'] = $request->file('video_file')->store('content', 'public');
        }
        if ($request->hasFile('copywriting_file')) {
            if ($content->copywriting_file) Storage::disk('public')->delete($content->copywriting_file);
            $data['copywriting_file'] = $request->file('copywriting_file')->store('content', 'public');
        }

        $content->update($data);

        if ($oldStatus !== $newStatus || $request->action === 'send_review') {
            ContentHistory::create([
                'content_id' => $content->id,
                'action_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => ($request->action == 'send_review') ? 'Content updated and submitted to review' : 'Content updated as draft',
            ]);
        }

        return redirect()->route('creator.contents')->with('success', 'Content updated successfully.');
    }

    public function destroy(Content $content)
    {
        if ($content->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action. You do not own this content.');
        }

        if ($content->thumbnail) Storage::disk('public')->delete($content->thumbnail);
        if ($content->video_file) Storage::disk('public')->delete($content->video_file);
        if ($content->copywriting_file) Storage::disk('public')->delete($content->copywriting_file);

        $content->delete();

        return redirect()->route('creator.contents')->with('success', 'Content deleted successfully.');
    }

    public function revisionNotes()
    {
        $userId = Auth::id();
        $contents = Content::where('created_by', $userId)
            ->where('status', 'rejected')
            ->latest()
            ->paginate(10);

        return view('creator.revisions', compact('contents'));
    }

    public function publishedContent()
    {
        $userId = Auth::id();
        $contents = Content::where('created_by', $userId)
            ->where('status', 'published')
            ->latest('publish_date')
            ->paginate(10);

        return view('creator.published', compact('contents'));
    }
}
