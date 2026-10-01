<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'sender_id', 'sender_role',
        'body', 'is_internal', 'is_edited', 'edited_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_edited'   => 'boolean',
        'edited_at'   => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withTrashed();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'message_id');
    }

    public function isFromAdmin(): bool
    {
        return $this->sender_role === 'admin';
    }

    public function isFromCustomer(): bool
    {
        return $this->sender_role === 'customer';
    }

    public function isInternal(): bool
    {
        return (bool) $this->is_internal;
    }
}
