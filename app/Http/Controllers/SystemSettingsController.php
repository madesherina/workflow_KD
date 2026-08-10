<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token', 'logo');

        // Checkboxes may not be sent if unchecked, so we handle them per section
        // Example for workflow section:
        if ($request->has('section')) {
            $section = $request->section;
            if ($section === 'workflow') {
                Setting::set('mandatory_review', $request->has('mandatory_review') ? '1' : '0');
                Setting::set('auto_publish', $request->has('auto_publish') ? '1' : '0');
            } elseif ($section === 'notification') {
                Setting::set('email_notif_new_content', $request->has('email_notif_new_content') ? '1' : '0');
                Setting::set('review_reminder_alerts', $request->has('review_reminder_alerts') ? '1' : '0');
                Setting::set('push_notifications', $request->has('push_notifications') ? '1' : '0');
            } elseif ($section === 'security') {
                Setting::set('require_2fa', $request->has('require_2fa') ? '1' : '0');
            }
        }

        // For other normal text/select inputs
        foreach ($settings as $key => $value) {
            if ($key !== 'section') {
                Setting::set($key, $value);
            }
        }

        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $file = $request->file('logo');
            $filename = 'app_logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            $path = public_path('uploads/system');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $file->move($path, $filename);
            
            // Delete old logo
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && file_exists(public_path('uploads/system/' . $oldLogo))) {
                unlink(public_path('uploads/system/' . $oldLogo));
            }

            Setting::set('app_logo', $filename);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Logo updated successfully.',
                    'logo_url' => asset('uploads/system/' . $filename)
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
