<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class leaveAbsenceRequestComment extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        'created_at' => 'date:d/m/Y',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachments'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('leave-absence-request-comments');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('leave-absence-request-comments');
    }
}
