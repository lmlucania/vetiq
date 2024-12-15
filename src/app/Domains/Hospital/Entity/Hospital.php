<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Entity;

use App\Domains\Hospital\ValueObjects\Address;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\HospitalUuid;
use App\Domains\Hospital\ValueObjects\IsPublished;
use App\Domains\Hospital\ValueObjects\Name;
use App\Domains\Hospital\ValueObjects\Phone;
use App\Domains\Hospital\ValueObjects\Zipcode;

class Hospital
{
    public function __construct(
        private HospitalId $id,
        private HospitalUuid $uuid,
        private Name $name,
        private Zipcode $zipcode,
        private Address $address,
        private Phone $phone,
        private IsPublished $isPublished,
    ) {
    }

    public function getId(): HospitalId
    {
        return $this->id;
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

    public function update(string $name, string $zipcode, string $address, string $phone, bool $isPublished): Hospital
    {
        return new $this(
            id:$this->id,
            uuid:$this->uuid,
            name:new Name($name),
            zipcode: new Zipcode($zipcode),
            address: new Address($address),
            phone: new Phone($phone),
            isPublished: new IsPublished($isPublished),
        );
    }
}
