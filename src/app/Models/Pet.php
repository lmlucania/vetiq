<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Pet\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'gender',
        'birthday',
        'started_care_at',
        'remark',
    ];

    protected $casts = [
        'gender'          => Gender::class,
        'birthday'        => 'date',
        'started_care_at' => 'date',
    ];
}
