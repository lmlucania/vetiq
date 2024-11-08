<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domains\Hospital\ValueObjects\Address;
use App\Domains\Hospital\ValueObjects\HospitalUuid;
use App\Domains\Hospital\ValueObjects\IsPublished;
use App\Domains\Hospital\ValueObjects\Name;
use App\Domains\Hospital\ValueObjects\Phone;
use App\Domains\Hospital\ValueObjects\Zipcode;

class HospitalDto
{
    public function __construct(
        private HospitalUuid $uuid,
        private Name $name,
        private Zipcode $zipcode,
        private Address $address,
        private Phone $phone,
        private IsPublished $isPublished,
    ) {
    }

    public function getUuid(): HospitalUuid
    {
        return $this->uuid;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getZipcode(): Zipcode
    {
        return $this->zipcode;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getIsPublished(): IsPublished
    {
        return $this->isPublished;
    }
}
