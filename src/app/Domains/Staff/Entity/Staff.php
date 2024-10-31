<?php

declare(strict_types=1);

namespace App\Domains\Staff\Entity;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Staff\ValueObjects\Email;
use App\Domains\Staff\ValueObjects\Name;
use App\Domains\Staff\ValueObjects\StaffId;

class Staff
{
    public function __construct(
        private StaffId $staffId,
        private HospitalId $hospitalId,
        private Name $name,
        private Email $email,
    )
    {
    }

    public function getStaffId(): StaffId
    {
        return $this->staffId;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
