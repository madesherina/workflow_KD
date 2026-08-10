<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('publish_queues')) {
            DB::statement("ALTER TABLE publish_queues MODIFY queue_status VARCHAR(255) DEFAULT 'waiting'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('publish_queues')) {
            DB::statement("ALTER TABLE publish_queues MODIFY queue_status ENUM('waiting', 'published', 'cancelled') DEFAULT 'waiting'");
        }
    }
};
