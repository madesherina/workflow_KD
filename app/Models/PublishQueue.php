<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishQueue extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'content_id',
        'scheduled_at',
        'queue_status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
