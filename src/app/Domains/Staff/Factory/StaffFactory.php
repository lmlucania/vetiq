<?php

declare(strict_types=1);

namespace App\Domains\Staff\Factory;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Staff\Entity\Staff;
use App\Domains\Staff\ValueObjects\Email;
use App\Domains\Staff\ValueObjects\Name;
use App\Domains\Staff\ValueObjects\StaffId;

class StaffFactory
{
    public static function fromAuthUser(): Staff
    {
        $staff = auth()->user();

        return new Staff(
            staffId: new StaffId($staff->id),
            hospitalId: new HospitalId($staff->hospital_id),
            name: new Name($staff->name),
            email: new Email($staff->email),
        );
    }
}
