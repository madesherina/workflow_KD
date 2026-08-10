<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Content;
use App\Models\User;
use App\Models\ContentHistory;

$creator = User::where('email', 'creator@example.com')->first();
$verifier = User::where('email', 'verifier@example.com')->first();
$publisher = User::where('email', 'publisher@example.com')->first();

if ($creator) {
    echo "Seeding contents for creator ID: {$creator->id}" . PHP_EOL;

    // 1. Draft Content
    $draft = Content::create([
        'title' => 'E-Commerce Summer Banner Idea',
        'description' => 'Draft brainstorming for the new Summer Sale.',
        'content' => 'Lorem ipsum Summer copywriting draft content text...',
        'content_type' => 'image',
        'status' => 'draft',
        'created_by' => $creator->id,
        'approved_by' => null,
        'publish_date' => null,
    ]);
    ContentHistory::create([
        'content_id' => $draft->id,
        'action_by' => $creator->id,
        'old_status' => null,
        'new_status' => 'draft',
        'note' => 'Brainstorming draft content created'
    ]);

    // 2. Waiting Review Content
    $review = Content::create([
        'title' => 'Mobile App Launch Promo Video',
        'description' => 'Promotional teaser for Mobile App v2.0.',
        'content' => 'Exciting news! The NexPublish mobile app version 2.0 is launching soon!',
        'content_type' => 'video',
        'status' => 'review',
        'created_by' => $creator->id,
        'approved_by' => null,
        'publish_date' => null,
    ]);
    ContentHistory::create([
        'content_id' => $review->id,
        'action_by' => $creator->id,
        'old_status' => null,
        'new_status' => 'review',
        'note' => 'Asset submitted to Verifier review queue'
    ]);

    // 3. Rejected Content
    $rejected = Content::create([
        'title' => 'Newsletter Week 24 Copywriting',
        'description' => 'Weekly newsletter copy.',
        'content' => 'Old copyrighting body with wrong promo codes.',
        'content_type' => 'mixed',
        'status' => 'rejected',
        'created_by' => $creator->id,
        'approved_by' => null,
        'publish_date' => null,
        'rejection_note' => 'Please correct the discount percentage (change from 20% to 15%) and re-submit.',
    ]);
    ContentHistory::create([
        'content_id' => $rejected->id,
        'action_by' => $creator->id,
        'old_status' => null,
        'new_status' => 'draft',
        'note' => 'Created initial newsletter copy'
    ]);
    ContentHistory::create([
        'content_id' => $rejected->id,
        'action_by' => $verifier ? $verifier->id : $creator->id,
        'old_status' => 'review',
        'new_status' => 'rejected',
        'note' => 'Verifier found incorrect promo details'
    ]);

    // 4. Published Content
    $published = Content::create([
        'title' => 'NexPublish Brand Guide Infographics',
        'description' => 'Official corporate identity and design assets.',
        'content' => 'NexPublish official identity details, colors, fonts, and guidelines.',
        'content_type' => 'image',
        'status' => 'published',
        'created_by' => $creator->id,
        'approved_by' => $verifier ? $verifier->id : null,
        'published_by' => $publisher ? $publisher->id : null,
        'publish_date' => now()->subDay(),
    ]);
    ContentHistory::create([
        'content_id' => $published->id,
        'action_by' => $creator->id,
        'old_status' => null,
        'new_status' => 'draft',
        'note' => 'Brand guides drafted'
    ]);
    ContentHistory::create([
        'content_id' => $published->id,
        'action_by' => $publisher ? $publisher->id : $creator->id,
        'old_status' => 'approved',
        'new_status' => 'published',
        'note' => 'Asset officially published live'
    ]);

    echo "Seeding completed successfully!" . PHP_EOL;
}
