<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Appointment;

use App\Http\Requests\Base\ApiRequest;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class StoreAppointmentRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id'         => ['required', 'integer', 'exists:pets,id'],
            'hospital_id'    => ['required', 'integer', 'exists:hospitals,id'],
            'menu_id'        => ['required', 'integer', 'exists:menus,id'],
            'vet_id'         => ['nullable', 'integer', 'exists:vets,id'],
            'appointment_at' => ['required', 'date'],
            'images'         => ['nullable', 'array'],
            'images.*'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function getPetId(): int
    {
        return $this->validated('pet_id');
    }

    public function getHospitalId(): int
    {
        return $this->validated('hospital_id');
    }

    public function getMenuId(): int
    {
        return $this->validated('menu_id');
    }

    public function getVetId(): ?int
    {
        return $this->validated('vet_id');
    }

    public function getAppointmentAt(): Carbon
    {
        return Carbon::parse($this->validated('appointment_at'));
    }

    /**
     * @return UploadedFile[]
     */
    public function getImages(): array
    {
        // validated() だと null が返ることがあるので file() が安全
        return $this->file('images');
    }
}
