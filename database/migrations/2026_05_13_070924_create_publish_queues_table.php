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
        if (!Schema::hasTable('publish_queues')) {
            Schema::create('publish_queues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('content_id')->constrained()->onDelete('cascade');
                $table->timestamp('scheduled_at')->nullable();
                $table->string('queue_status')->default('waiting'); // waiting, scheduled, published, cancelled
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publish_queues');
    }
};
