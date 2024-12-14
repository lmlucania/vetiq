<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Factory;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\ValueObjects\Address;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\HospitalUuid;
use App\Domains\Hospital\ValueObjects\IsPublished;
use App\Domains\Hospital\ValueObjects\Name;
use App\Domains\Hospital\ValueObjects\Phone;
use App\Domains\Hospital\ValueObjects\Zipcode;
use App\Models\HospitalModel;

class HospitalFactory
{
    public static function createEntityFromAuthStaff(): Hospital
    {
        $hospitalModel = auth()->user()->hospital;

        return new Hospital(
            new HospitalId($hospitalModel->id),
            new HospitalUuid($hospitalModel->uuid),
            new Name($hospitalModel->name),
            new Zipcode($hospitalModel->zipcode),
            new Address($hospitalModel->address),
            new Phone($hospitalModel->phone),
            new IsPublished($hospitalModel->is_published),
        );
    }

    public function modelToEntity(HospitalModel $hospitalModel): Hospital
    {
        return new Hospital(
            new HospitalId($hospitalModel->id),
            new HospitalUuid($hospitalModel->uuid),
            new Name($hospitalModel->name),
            new Zipcode($hospitalModel->zipcode),
            new Address($hospitalModel->address),
            new Phone($hospitalModel->phone),
            new IsPublished($hospitalModel->is_published),
        );
    }

    public function entityToModel(Hospital $hospital): HospitalModel
    {
        $hospitalModel = new HospitalModel();

        $hospitalModel->id           = $hospital->getId()->getValue();
        $hospitalModel->uuid         = $hospital->getUuid()->getValue();
        $hospitalModel->name         = $hospital->getName()->getValue();
        $hospitalModel->zipcode      = $hospital->getZipcode()->getValue();
        $hospitalModel->address      = $hospital->getAddress()->getValue();
        $hospitalModel->phone        = $hospital->getPhone()->getValue();
        $hospitalModel->is_published = $hospital->getIsPublished()->getValue();

        return $hospitalModel;
    }

    public function createEntity(
        int $id,
        string $uuid,
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): Hospital {
        return new Hospital(
            new HospitalId($id),
            new HospitalUuid($uuid),
            new Name($name),
            new Zipcode($zipcode),
            new Address($address),
            new Phone($phone),
            new IsPublished($isPublished),
        );
    }
}
