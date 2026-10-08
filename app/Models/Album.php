<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Album extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'artist',
        'description',
        'cover_url',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'artist', 'description', 'cover_url'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('album');
    }
}