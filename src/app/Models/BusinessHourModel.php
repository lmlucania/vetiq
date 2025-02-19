<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHourModel extends Model
{
    use HasFactory;

    protected $table    = 'business_hours';
    protected $fillable = [
        'hospital_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'start_time'  => 'datetime:H:i',
            'end_time'    => 'datetime:H:i',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(HospitalModel::class);
    }
}
