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
        'uuid',
        'hospital_id',
        'user_id',
        'rating',
        'title',
        'body',
    ];

    protected $casts = [
        'rating' => Rating::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(HospitalModel::class, 'hospital_id', 'id');
    }
}
