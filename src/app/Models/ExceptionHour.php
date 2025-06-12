<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Schedule\Enum\TimePeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'date',
        'time_period',
        'start_time',
        'end_time',
        'is_closed',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'time_period' => TimePeriod::class,
            'start_time'  => 'datetime:H:i',
            'end_time'    => 'datetime:H:i',
            'is_closed'   => 'boolean',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
