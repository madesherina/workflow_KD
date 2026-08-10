<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'description',
        'thumbnail',
        'images',
        'video_file',
        'copywriting_file',
        'content_type',
        'status',
        'created_by',
        'approved_by',
        'published_by',
        'publish_date',
        'scheduled_at',
        'rejection_note',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'scheduled_at' => 'datetime',
        'images' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function histories()
    {
        return $this->hasMany(ContentHistory::class);
    }

    public function publishQueues()
    {
        return $this->hasMany(PublishQueue::class);
    }
}
