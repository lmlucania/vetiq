<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'uuid', 'hospital_id', 'last_name', 'first_name', 'accept_appointment', 'remark'];

    protected function casts(): array
    {
        return [
            'accept_appointment' => 'bool',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
