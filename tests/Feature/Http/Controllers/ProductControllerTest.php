<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Notification\ProductStoreNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductController
 */
class ProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('product.index'));

        $response->assertOk();
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', $product));

        $response->assertOk();
        $response->assertViewIs('products.show');
        $response->assertViewHas('product');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'store',
            \App\Http\Requests\ProductStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $title = $this->faker->sentence(4);
        $price = $this->faker->numberBetween(-10000, 10000);
        $is_available = $this->faker->boolean;

        Notification::fake();

        $response = $this->post(route('product.store'), [
            'title' => $title,
            'price' => $price,
            'is_available' => $is_available,
        ]);

        $products = Product::query()
            ->where('title', $title)
            ->where('price', $price)
            ->where('is_available', $is_available)
            ->get();
        $this->assertCount(1, $products);
        $product = $products->first();

        $response->assertRedirect(route('products.index'));

        Notification::assertSentTo($product->user, ProductStoreNotification::class, function ($notification) use ($product) {
            return $notification->product->is($product);
        });
    }
}
