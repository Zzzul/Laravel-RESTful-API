<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
        // cara 1
        // $products = Product::paginate(10);

        // return response([
        //     'message' => 'Get all products successfully.',
        //     'products' => $products
        //     // bisa juga 200
        // ], Response::HTTP_OK);

        // cara 2 menggunakan resource API
        return new ProductCollection(Product::paginate(10));
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

            $request->photo->storeAs('img/photo/', $filename);

            $data['photo'] = $filename;
        }

        $prodcut = Product::create($data);

        return new ProductResource($prodcut);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // cara 1
        $prodcut = Product::find($id);

        if (!$prodcut) {
            return response(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }

        // cara 2
        // $prodcut = Product::findOrFail($id);

        return new ProductResource($prodcut);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $prodcut = Product::findOrFail($id);

        $data = $request->validated();

        if ($request->has('photo')) {
            $filename = Str::slug($request->name) . '.' . $request->photo->extension();

            $request->photo->storeAs('img/photo/', $filename);

            $data['photo'] = $filename;

            // hapus photo lama dari storage
            Storage::delete('img/photo/' . $prodcut->photo);
        }

        $prodcut->update($data);

        return new ProductResource($prodcut);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prodcut = Product::findOrFail($id);

        Storage::delete('img/photo/' . $prodcut->photo);

        $prodcut->delete();

        return response(['message' => 'Product deleted successfully.'], Response::HTTP_OK);
    }
}
