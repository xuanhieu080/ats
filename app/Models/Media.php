<?php

namespace App\Models;

use App\Helpers\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'code',
        'link',
        'path',
        'status',
        'size',
        'extension',
        'mime_type',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();
        // Write Log

        static::deleting(function ($model) {
            HasImage::deleteImage($model->path);
        });
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
