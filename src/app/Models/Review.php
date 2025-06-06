<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Review\Enum\Rating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'user_id',
        'title',
        'body',
    ];

    protected $casts = [
        'rating' => Rating::class,
    ];
}
