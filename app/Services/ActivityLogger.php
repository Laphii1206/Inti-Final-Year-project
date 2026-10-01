<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        Model $subject,
        string $category,
        string $event,
        string $description,
        ?Model $causer = null,
        array $properties = []
    ): ActivityLog {
        return ActivityLog::create([
            'loggable_type' => get_class($subject),
            'loggable_id'   => $subject->getKey(),
            'user_id'       => $causer?->id ?? Auth::id(),
            'action'        => $category . '.' . $event,
            'description'   => $description,
        ]);
    }

    public static function booking(Model $subject, string $event, string $description, array $properties = []): ActivityLog
    {
        return self::log($subject, 'booking', $event, $description, null, $properties);
    }

    public static function system(Model $subject, string $event, string $description, array $properties = []): ActivityLog
    {
        return self::log($subject, 'system', $event, $description, null, $properties);
    }

    public static function admin(Model $subject, string $event, string $description, array $properties = []): ActivityLog
    {
        return self::log($subject, 'admin', $event, $description, null, $properties);
    }
}