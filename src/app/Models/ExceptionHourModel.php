<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionHourModel extends Model
{
    use HasFactory;

    protected $table    = 'exception_hours';
    protected $fillable = [
        'hospital_id',
        'date',
        'start_time',
        'end_time',
        'is_closed',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'date'       => 'date',
            'start_time' => 'datetime:H:i',
            'end_time'   => 'datetime:H:i',
            'is_closed'  => 'boolean',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(HospitalModel::class);
    }
}
