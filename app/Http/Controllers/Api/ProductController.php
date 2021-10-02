<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prodcucts = Product::with('category')->paginate(10);

        return new ProductCollection($prodcucts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->has('photo')) {
            $filename = Str::slug($request->name) . '.' . $request->photo->extension();

            $request->photo->storeAs('public/img/photo/', $filename);

            $data['photo'] = $filename;
        }

        $product = Product::with('category')->create($data);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->has('photo')) {
            $filename = Str::slug($request->name) . '.' . $request->photo->extension();

            $request->photo->storeAs('img/photo/', $filename);

            $data['photo'] = $filename;

            // hapus photo lama dari storage
            Storage::delete('public/img/photo/' . $product->photo);
        }

        $product->update($data);

        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Storage::delete('public/img/photo/' . $product->photo);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ], Response::HTTP_OK);
    }
}
