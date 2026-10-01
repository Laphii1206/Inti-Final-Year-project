<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeleteAccountRequest extends Model
{
    protected $fillable = [
        'user_id', 'reason', 'status',
        'reviewed_by', 'reviewed_at', 'admin_notes',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function user()       { return $this->belongsTo(User::class); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
}