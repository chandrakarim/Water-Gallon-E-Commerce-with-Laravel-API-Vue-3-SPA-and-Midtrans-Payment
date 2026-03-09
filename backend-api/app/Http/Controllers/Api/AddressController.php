<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()
            ->addresses()
            ->latest()
            ->get();

        return ApiResponse::success(AddressResource::collection($addresses));
    }

    public function store(StoreAddressRequest $request)
    {
        $address = $request->user()->addresses()->create($request->validated());

        return ApiResponse::success(
            new AddressResource($address),
            'Alamat berhasil ditambahkan',
            201
        );
    }

    public function show(Address $address)
    {
        $this->authorize('view', $address);

        return ApiResponse::success(new AddressResource($address));
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $this->authorize('update', $address);

        $address->update($request->validated());

        return ApiResponse::success(new AddressResource($address), 'Alamat berhasil diupdate');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        $address->delete();

        return ApiResponse::success(null, 'Alamat berhasil dihapus');
    }
}
