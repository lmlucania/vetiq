<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'hospital_id',
        'title',
        'detail',
        'is_published',
        'date',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'date'         => 'date',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
