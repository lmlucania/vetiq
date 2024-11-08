<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'hospitals';
    protected $fillable = ['id', 'uuid', 'name', 'zipcode', 'address', 'phone', 'is_published'];

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
}
