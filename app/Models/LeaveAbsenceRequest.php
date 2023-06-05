<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LeaveAbsenceRequest extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachments'];


    protected $casts = [
        'starts_at' => 'date:d/m/Y',
        'ends_at' => 'date:d/m/Y',
        'approved_at' => 'date:d/m/Y',
        'rejected_at' => 'date:d/m/Y',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('leave-absence-requests');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function comments()
    {
        return $this->hasMany(LeaveAbsenceRequestComment::class);
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('leave-absence-requests');
    }
}
