<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function ($model) use ($eventName) {
                $model->logActivity($eventName);
            });
        }
    }

    protected static function getRecordActivityEvents()
    {
        return ['created', 'updated', 'deleted'];
    }

    public function logActivity($eventName)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $eventName,
            'description' => "{$eventName} " . strtolower(class_basename($this)),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'ip_address' => request()->ip(),
        ]);
    }
}
