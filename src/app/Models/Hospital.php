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
        'name',
        'phone',
        'post_code',
        'prefecture',
        'address1',
        'address2',
        'is_published',
        'image_path',
        ];

    protected function casts(): array
    {
        return [
            'is_published' => 'bool',
        ];
    }

    public function favoredByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'hospital_id',
            'user_id',
        )->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function businessHours()
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function exceptionHours()
    {
        return $this->hasMany(ExceptionHour::class);
    }
}
