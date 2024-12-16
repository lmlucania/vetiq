<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Staff\Entity\Staff;
use App\Domains\Staff\ValueObjects\Email;
use App\Domains\Staff\ValueObjects\Name;
use App\Domains\Staff\ValueObjects\StaffId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;

class StaffModel extends User
{
    use HasFactory, SoftDeletes;

    protected $table = 'staffs';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hospital()
    {
        return $this->belongsTo(HospitalModel::class);
    }
}
