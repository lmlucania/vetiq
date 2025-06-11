<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'phone',
        'post_code',
        'prefecture',
        'address1',
        'address2',
        'is_published',
        ];

    protected function casts(): array
    {
        return [
            'is_published' => 'bool',
        ];
    }

    public function staffs()
    {
        return $this->hasMany(StaffModel::class);
    }

    public function favoredByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'hospital_id',
            'user_id',
        )
            ->withTimestamps();
    }
}
