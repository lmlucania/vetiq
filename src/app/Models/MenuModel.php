<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'menus';
    protected $fillable = ['hospital_id', 'name', 'email', 'required_time', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'bool',
        ];
    }

    public function hospital()
    {
        return $this->belongsTo(HospitalModel::class);
    }
}
