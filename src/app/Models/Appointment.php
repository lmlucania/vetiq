<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'hospital_id',
        'menu_id',
        'vet_id',
        'appointment_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_at' => 'datetime:Y-m-d H:i',
        ];
    }

    public function statusHistories()
    {
        return $this->hasMany(AppointmentStatusHistory::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function vet()
    {
        return $this->belongsTo(Vet::class);
    }
}
