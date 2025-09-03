<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

use Illuminate\Http\UploadedFile;

class HospitalImageDto
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?UploadedFile $file,
        public readonly int $displayOrder,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }
}
