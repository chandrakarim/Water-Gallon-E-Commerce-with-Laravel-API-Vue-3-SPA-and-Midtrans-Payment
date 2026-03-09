<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return ApiResponse::success(
            ProductResource::collection(Product::latest()->get())
        );
    }

    public function publicProducts()
    {
        $products = Product::latest()->get();

        return ApiResponse::success(
            $products,
            'Produk publik berhasil diambil'
        );
    }

    public function show(Product $product)
    {
        return ApiResponse::success(
            new ProductResource($product)
        );
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = $this->productService->store(
            $request->validated()
        );

        return ApiResponse::success(
            new ProductResource($product),
            'Product berhasil dibuat'
        );
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $updated = $this->productService->update(
            $product,
            $request->validated()
        );

        return ApiResponse::success(
            new ProductResource($updated),
            'Product berhasil diperbarui'
        );
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $this->productService->destroy($product);

        return ApiResponse::success(
            null,
            'Product berhasil dihapus'
        );
    }
}
