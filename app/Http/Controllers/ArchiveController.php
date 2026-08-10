<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Carbon\Carbon;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Content::with(['creator', 'publisher'])
            ->where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter Type
        if ($request->filled('type')) {
            $query->where('content_type', $request->type);
        }

        // Filter Date
        if ($request->filled('date')) {
            $query->whereDate('publish_date', $request->date);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->orderBy('publish_date', 'asc');
        } else {
            $query->orderBy('publish_date', 'desc');
        }

        $contents = $query->paginate(12);

        // Stats
        $stats = [
            'total' => Content::where('status', 'published')->count(),
            'today' => Content::where('status', 'published')->whereDate('publish_date', Carbon::today())->count(),
            'week' => Content::where('status', 'published')->whereBetween('publish_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
        ];

        $recentlyPublished = Content::where('status', 'published')->latest('publish_date')->take(5)->get();

        return view('archive.index', compact('contents', 'stats', 'recentlyPublished'));
    }
}
