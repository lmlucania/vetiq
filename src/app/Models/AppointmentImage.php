<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentImage extends Model
{
    protected $fillable = [
        'appointment_id',
        'image_path',
        'display_order',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
