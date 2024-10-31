<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\ValueObjects\Address;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\IsPublished;
use App\Domains\Hospital\ValueObjects\Name;
use App\Domains\Hospital\ValueObjects\Phone;
use App\Domains\Hospital\ValueObjects\PublicId;
use App\Domains\Hospital\ValueObjects\Zipcode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "hospitals";

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

    public function toEntity() {
        return new Hospital(
            new HospitalId($this->id),
            new PublicId($this->public_id),
            new Name($this->name),
            new Zipcode($this->zipcode),
            new Address($this->address),
            new Phone($this->phone),
            new IsPublished($this->is_published),
        );
    }
}
