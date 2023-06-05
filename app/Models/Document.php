<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachments'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents');
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('documents');
    }
}
