<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $table = 'review_image';

    protected $fillable = [
        'review_id',
        'image_path',
        'display_order',
        'created_at',
        'updated_at',
    ];
}
