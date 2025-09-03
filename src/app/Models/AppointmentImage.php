<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentImage extends Model
{
    protected $table = 'appointment_images';

    protected $fillable = [
        'appointment_id',
        'image_path',
        'display_order',
        'created_at',
        'updated_at',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
