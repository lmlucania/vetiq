<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\PetService;
use App\Http\Controllers\Controller;
use App\Transformers\PetTransformer;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct(
        private PetService $petService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petCollection = $this->petService->getMyPets();

        return fractal($petCollection, new PetTransformer())->respond();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $pet = $this->petService->getByUuid($uuid);

        return fractal($pet, new PetTransformer())->respond();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
