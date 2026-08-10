<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Content;
use App\Models\PublishQueue;
use App\Models\User;

$creator = User::where('email', 'admin@gmail.com')->first();
$verifier = User::where('email', 'verifier@example.com')->first();

if ($creator && $verifier) {
    // 1. Create an Approved content if not exists
    $approvedContent = Content::where('title', 'Product Launch Banner')->first();
    if (!$approvedContent) {
        $approvedContent = Content::create([
            'title' => 'Product Launch Banner',
            'description' => 'Marketing materials for the upcoming product launch.',
            'content' => 'Lorem ipsum approved content body.',
            'content_type' => 'image',
            'status' => 'approved',
            'created_by' => $creator->id,
            'approved_by' => $verifier->id,
            'publish_date' => null,
        ]);
        echo "Created approved content: 'Product Launch Banner'" . PHP_EOL;
    }

    // 2. Create another content to be Scheduled
    $scheduledContent = Content::where('title', 'Upcoming Promo Video')->first();
    if (!$scheduledContent) {
        $scheduledContent = Content::create([
            'title' => 'Upcoming Promo Video',
            'description' => 'Promotional teaser video for next month.',
            'content' => 'Lorem ipsum scheduled content body.',
            'content_type' => 'video',
            'status' => 'approved',
            'created_by' => $creator->id,
            'approved_by' => $verifier->id,
            'publish_date' => null,
        ]);
        echo "Created approved content for scheduling: 'Upcoming Promo Video'" . PHP_EOL;
    }

    // Ensure PublishQueue has a scheduled entry for the second content
    $queue = PublishQueue::where('content_id', $scheduledContent->id)->first();
    if (!$queue) {
        PublishQueue::create([
            'content_id' => $scheduledContent->id,
            'scheduled_at' => now()->addDays(3),
            'queue_status' => 'scheduled',
        ]);
        echo "Scheduled 'Upcoming Promo Video' in publish queue." . PHP_EOL;
    }
}
