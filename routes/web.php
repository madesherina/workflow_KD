<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PublishController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Redirect /dashboard to role-specific dashboard
    Route::get('/dashboard', function () {
        $userRole = strtolower(Auth::user()->role->role_name ?? '');
        if (str_contains($userRole, 'admin')) return redirect()->route('superadmin.dashboard');
        if (str_contains($userRole, 'verifier')) return redirect()->route('verifier.dashboard');
        if (str_contains($userRole, 'publisher')) return redirect()->route('publisher.dashboard');
        if (str_contains($userRole, 'creator')) return redirect()->route('creator.dashboard');
        return redirect()->route('login');
    });

    // Creator Routes
    Route::middleware(['role:Creator,creator,Super Admin,super_admin'])->group(function () {
        Route::get('/creator/dashboard', [App\Http\Controllers\CreatorDashboardController::class, 'index'])->name('creator.dashboard');
        Route::get('/creator/contents', [App\Http\Controllers\CreatorDashboardController::class, 'myContent'])->name('creator.contents');
        Route::post('/creator/contents', [App\Http\Controllers\CreatorDashboardController::class, 'store'])->name('creator.contents.store');
        Route::get('/creator/contents/{content}/edit', [App\Http\Controllers\CreatorDashboardController::class, 'edit'])->name('creator.contents.edit');
        Route::put('/creator/contents/{content}', [App\Http\Controllers\CreatorDashboardController::class, 'update'])->name('creator.contents.update');
        Route::delete('/creator/contents/{content}', [App\Http\Controllers\CreatorDashboardController::class, 'destroy'])->name('creator.contents.destroy');
        Route::get('/creator/revision-notes', [App\Http\Controllers\CreatorDashboardController::class, 'revisionNotes'])->name('creator.revisions');
        Route::get('/creator/published', [App\Http\Controllers\CreatorDashboardController::class, 'publishedContent'])->name('creator.published');
    });

    // Publisher Routes
    Route::middleware(['role:Publisher,publisher,Super Admin,super_admin'])->group(function () {
        Route::get('/publisher/dashboard', [App\Http\Controllers\PublisherDashboardController::class, 'index'])->name('publisher.dashboard');
        Route::get('/publisher/queue', [App\Http\Controllers\PublisherDashboardController::class, 'queue'])->name('publisher.queue');
        Route::get('/publisher/scheduled', [App\Http\Controllers\PublisherDashboardController::class, 'scheduled'])->name('publisher.scheduled');
        Route::get('/publisher/published', [App\Http\Controllers\PublisherDashboardController::class, 'published'])->name('publisher.published');
        Route::get('/publisher/logs', [App\Http\Controllers\PublisherDashboardController::class, 'logs'])->name('publisher.logs');
        
        // Actions from PublishController
        Route::post('/publisher/queue/{content}/publish-now', [PublishController::class, 'publishNow'])->name('publisher.publish.now');
        Route::post('/publisher/queue/{content}/schedule', [PublishController::class, 'schedule'])->name('publisher.publish.schedule');
        Route::post('/publisher/queue/{content}/cancel', [PublishController::class, 'cancel'])->name('publisher.publish.cancel');
    });

    // Super Admin Routes
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/super-admin/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('users', UserController::class);
        Route::get('/system-settings', [SystemSettingsController::class, 'index'])->name('settings.index');
        Route::post('/system-settings', [SystemSettingsController::class, 'update'])->name('settings.update');
        
        // Super Admin can also see publish queue
        Route::get('/publish-queue', [PublishController::class, 'index'])->name('publish.index');
        Route::post('/publish-queue/{content}/publish-now', [PublishController::class, 'publishNow'])->name('publish.now');
        Route::post('/publish-queue/{content}/schedule', [PublishController::class, 'schedule'])->name('publish.schedule');
        Route::post('/publish-queue/{content}/cancel', [PublishController::class, 'cancel'])->name('publish.cancel');
    });

    // Verifier & Admin Routes
    Route::middleware(['role:Verifier,verifier,Super Admin,super_admin'])->group(function () {
        Route::get('/verifier/dashboard', [App\Http\Controllers\VerifierDashboardController::class, 'index'])->name('verifier.dashboard');
        Route::get('/review-queue', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/review-approved', [ReviewController::class, 'approved'])->name('reviews.approved');
        Route::get('/review-rejected', [ReviewController::class, 'rejected'])->name('reviews.rejected');
        Route::post('/review-queue/{content}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/review-queue/{content}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        
        // Verifier can see history and profile (profile is shared below)
        Route::get('/review-history', [ArchiveController::class, 'index'])->name('reviews.history');
    });

    // Super Admin & Creator Content Management
    Route::middleware(['role:Super Admin,super_admin,creator,Creator'])->group(function () {
        Route::resource('contents', ContentController::class)->except(['show']);
    });

    // Everyone can view content details and update status
    Route::get('/contents/{content}', [ContentController::class, 'show'])->name('contents.show');
    Route::post('/contents/{content}/status', [ContentController::class, 'updateStatus'])->name('contents.status');
    Route::get('/contents/{content}/download/{type}', [ContentController::class, 'downloadFile'])->name('contents.download');
    Route::get('/contents/{content}/download-zip', [ContentController::class, 'downloadZip'])->name('contents.download_zip');

    Route::get('/published-content', [ArchiveController::class, 'index'])->name('archive.index');

    // Shared Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
