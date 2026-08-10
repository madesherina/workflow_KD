<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userRole = strtolower($user->role->role_name ?? '');

        // Initialize query with relations
        $query = Content::with(['creator', 'approver', 'publisher']);

        // Role-based access control (mimicking Blade behavior)
        if (str_contains($userRole, 'creator')) {
            // Creators only see their own contents
            $query->where('created_by', $user->id);
        } elseif (str_contains($userRole, 'admin')) {
            // Super Admins see all contents
            // No additional where clause needed
        } else {
            // Verifiers and Publishers have specific queues in Blade,
            // but if they access the general contents API, we restrict them
            // or return empty/forbidden depending on business needs.
            // For safety based on web.php `Role:Super Admin,creator`, 
            // they shouldn't access this general list.
            return response()->json([
                'message' => 'Unauthorized access to general contents list.'
            ], 403);
        }

        // Apply filters (mimicking ContentController & CreatorDashboardController)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination (CreatorDashboard uses paginate(10), ContentController uses get(). 
        // We standardise to paginate for API).
        $perPage = $request->get('per_page', 10);
        $contents = $query->latest()->paginate($perPage);

        return response()->json($contents, 200);
    }
}
