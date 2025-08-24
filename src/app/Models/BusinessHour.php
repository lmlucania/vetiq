<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Schedule\Enum\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => DayOfWeek::class,
            'start_time'  => 'datetime:H:i',
            'end_time'    => 'datetime:H:i',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
