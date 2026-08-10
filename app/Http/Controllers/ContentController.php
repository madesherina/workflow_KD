<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ContentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $query = Content::with('creator');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contents = $query->latest()->get();
        return view('contents.index', compact('contents'));
    }

    public function show(Content $content)
    {
        $content->load(['creator', 'approver', 'publisher', 'histories.actor']);
        return view('contents.show', compact('content'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:image,video,mixed',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov|max:51200',
            'copywriting_file' => 'nullable|mimes:pdf,docx|max:10240',
            'scheduled_at' => 'nullable|date',
            'action' => 'required|in:save_draft,send_review',
        ]);

        $data = $request->only(['title', 'description', 'content_type', 'content', 'scheduled_at']);
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
            'note' => ($request->action == 'send_review') ? 'Content created and sent to review' : 'Content created as draft',
        ]);

        return redirect()->route('contents.index')->with('success', 'Content saved successfully.');
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:image,video,mixed',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov|max:51200',
            'copywriting_file' => 'nullable|mimes:pdf,docx|max:10240',
            'scheduled_at' => 'nullable|date',
            'action' => 'nullable|in:save_draft,send_review',
        ]);

        $data = $request->only(['title', 'description', 'content_type', 'content', 'scheduled_at']);
        
        if ($request->action == 'send_review') {
            $data['status'] = 'review';
            
            ContentHistory::create([
                'content_id' => $content->id,
                'action_by' => Auth::id(),
                'old_status' => $content->status,
                'new_status' => 'review',
                'note' => 'Status changed to review during update',
            ]);
        }

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

        return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
    }

    public function updateStatus(Request $request, Content $content)
    {
        $request->validate([
            'status' => 'required|in:draft,review,approved,published,rejected',
            'rejection_note' => 'nullable|string',
        ]);

        $oldStatus = $content->status;
        $updateData = ['status' => $request->status];

        if ($request->status == 'approved') {
            $updateData['approved_by'] = Auth::id();
        } elseif ($request->status == 'published') {
            $updateData['published_by'] = Auth::id();
            $updateData['publish_date'] = now();
        } elseif ($request->status == 'rejected') {
            $updateData['rejection_note'] = $request->rejection_note;
        }

        $content->update($updateData);

        ContentHistory::create([
            'content_id' => $content->id,
            'action_by' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'note' => $request->rejection_note ?? 'Status changed to ' . $request->status,
        ]);

        return redirect()->back()->with('success', 'Content status updated to ' . $request->status);
    }

    public function destroy(Content $content)
    {
        if ($content->thumbnail) Storage::disk('public')->delete($content->thumbnail);
        if ($content->video_file) Storage::disk('public')->delete($content->video_file);
        if ($content->copywriting_file) Storage::disk('public')->delete($content->copywriting_file);
        
        $content->delete();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    }

    public function downloadFile(Request $request, Content $content, $type)
    {
        $validTypes = ['thumbnail', 'video_file', 'copywriting_file', 'image'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        if ($type === 'image') {
            $index = $request->query('index');
            if (is_array($content->images) && isset($content->images[$index])) {
                $filePath = $content->images[$index];
            } else {
                return redirect()->back()->with('error', 'Image not found.');
            }
        } else {
            $filePath = $content->$type;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function downloadZip(Content $content)
    {
        $zip = new \ZipArchive();
        $fileName = 'Content_' . $content->id . '_' . \Str::slug($content->title) . '.zip';
        $zipPath = storage_path('app/public/' . $fileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            
            // Add text content
            $textContent = "Title: " . $content->title . "\n";
            $textContent .= "Description: " . $content->description . "\n\n";
            $textContent .= "Content / Copywriting:\n" . $content->content;
            $zip->addFromString('content_text.txt', $textContent);

            // Add thumbnail
            if ($content->thumbnail && Storage::disk('public')->exists($content->thumbnail)) {
                $zip->addFile(Storage::disk('public')->path($content->thumbnail), 'thumbnail_' . basename($content->thumbnail));
            }

            // Add additional images
            if (is_array($content->images)) {
                foreach ($content->images as $index => $img) {
                    if (Storage::disk('public')->exists($img)) {
                        $zip->addFile(Storage::disk('public')->path($img), 'image_' . ($index + 1) . '_' . basename($img));
                    }
                }
            }

            // Add video
            if ($content->video_file && Storage::disk('public')->exists($content->video_file)) {
                $zip->addFile(Storage::disk('public')->path($content->video_file), 'video_' . basename($content->video_file));
            }

            // Add copywriting document
            if ($content->copywriting_file && Storage::disk('public')->exists($content->copywriting_file)) {
                $zip->addFile(Storage::disk('public')->path($content->copywriting_file), 'document_' . basename($content->copywriting_file));
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
