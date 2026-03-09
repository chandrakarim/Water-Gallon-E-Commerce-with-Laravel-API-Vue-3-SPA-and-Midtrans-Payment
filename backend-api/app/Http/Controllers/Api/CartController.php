<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateQtyRequest;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $service;

    public function __construct(CartService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ApiResponse::success(
            new CartResource($this->service->getCart(Auth::user()))
        );
    }

    public function store(StoreCartRequest $request)
    {
        $this->service->add(Auth::user(), $request->product_id, $request->qty);

        return ApiResponse::success(
            new CartResource($this->service->getCart(Auth::user())),
            'Produk ditambahkan ke keranjang'
        );
    }

    public function update(UpdateQtyRequest $request, $item)
    {
        $this->service->updateQty(Auth::user(), $item, $request->qty);

        return ApiResponse::success(
            new CartResource($this->service->getCart(Auth::user())),
            'Qty diperbarui'
        );
    }

    public function destroy($item)
    {
        $this->service->removeItem(Auth::user(), $item);

        return ApiResponse::success(null, 'Item dihapus');
    }

    public function clear()
    {
        $this->service->clearCart(Auth::user());

        return ApiResponse::success(null, 'Keranjang dikosongkan');
    }
}
