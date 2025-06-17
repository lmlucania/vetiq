<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Appointment\Enum\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'status',
        'modifier_type',
        'modifier_id',
        'hospital_memo',
    ];

    protected function casts(): array
    {
        return [
            'status' => AppointmentStatus::class,
        ];
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
