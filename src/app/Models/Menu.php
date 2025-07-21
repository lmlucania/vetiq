<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hospital_id',
        'name',
        'detail',
        'required_time',
        'is_published',
        ];

    protected function casts(): array
    {
        return [
            'is_published' => 'bool',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
