<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentHistory extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'content_id',
        'old_status',
        'new_status',
        'action_by',
        'note',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
