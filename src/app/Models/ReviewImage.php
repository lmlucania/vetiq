<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $table = 'review_images';

    protected $fillable = [
        'review_id',
        'image_path',
        'display_order',
        'created_at',
        'updated_at',
    ];
}
