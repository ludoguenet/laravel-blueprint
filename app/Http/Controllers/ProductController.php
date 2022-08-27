<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Notification\ProductStoreNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * @param \App\Http\Requests\ProductStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());

        Notification::send($product->user, new ProductStoreNotification($product));

        return redirect()->route('products.index');
    }
}
