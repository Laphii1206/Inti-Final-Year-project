<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    const STATUS_OPEN        = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED    = 'resolved';
    const STATUS_CLOSED      = 'closed';

    const TYPE_SERVICE_QUALITY = 'service_quality';
    const TYPE_OVERCHARGE      = 'overcharge';
    const TYPE_PARTS_ISSUE     = 'parts_issue';
    const TYPE_GENERAL         = 'general';
    const TYPE_OTHER           = 'other';

    const PRIORITY_LOW    = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH   = 'high';
    const PRIORITY_URGENT = 'urgent';

    const TYPES = [
        self::TYPE_SERVICE_QUALITY => 'Service Quality Issue',
        self::TYPE_OVERCHARGE      => 'Overcharge / Billing Dispute',
        self::TYPE_PARTS_ISSUE     => 'Parts / Components Issue',
        self::TYPE_GENERAL         => 'General Inquiry',
        self::TYPE_OTHER           => 'Other',
    ];

    const STATUSES = [
        self::STATUS_OPEN        => 'Open',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_RESOLVED    => 'Resolved',
        self::STATUS_CLOSED      => 'Closed',
    ];

    const PRIORITIES = [
        self::PRIORITY_LOW    => 'Low',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_HIGH   => 'High',
        self::PRIORITY_URGENT => 'Urgent',
    ];

    const PRIORITY_COLORS = [
        self::PRIORITY_LOW    => 'info',
        self::PRIORITY_MEDIUM => 'warning',
        self::PRIORITY_HIGH   => 'danger',
        self::PRIORITY_URGENT => 'danger',
    ];

    protected $fillable = [
        'ticket_number', 'user_id', 'booking_id', 'assigned_to',
        'type', 'subject', 'status', 'priority',
        'rating', 'feedback', 'tags', 'is_locked',
        'description', 'admin_reply', 'replied_by', 'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'tags'       => 'array',
        'is_locked'  => 'boolean',
    ];

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to')->withTrashed();
    }

    protected static function booted(): void
    {
        static::creating(function (self $ticket): void {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    public static function generateTicketNumber(): string
    {
        $date = now()->format('ymd');
        $pool = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // Excludes l, I, i, L, o, O, 0, 1
        do {
            $random = substr(str_shuffle($pool), 0, 4);
            $number = $date . $random;
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class)->withTrashed();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at');
    }

    public function publicMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')
                    ->where('is_internal', false)
                    ->orderBy('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getPriorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst($this->priority ?? 'medium');
    }

    public function getPriorityColor(): string
    {
        return self::PRIORITY_COLORS[$this->priority] ?? 'warning';
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function canReceiveMessages(): bool
    {
        return !$this->is_locked && in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_RESOLVED]);
    }

    public function getLastMessagePreview(): string
    {
        $last = $this->messages()->latest()->first();
        if (!$last) return $this->subject;
        return \Str::limit($last->body, 60);
    }

    public function getUnreadAdminCount(): int
    {
        $lastAdminMessage = $this->messages()
            ->where('sender_role', 'admin')
            ->latest()
            ->first();

        $query = $this->messages()->where('sender_role', 'customer');
        if ($lastAdminMessage) {
            $query->where('created_at', '>', $lastAdminMessage->created_at);
        }
        return $query->count();
    }
}
