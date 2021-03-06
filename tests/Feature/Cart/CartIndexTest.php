<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    /** @test */
    function it_fails_if_unauthenticated()
    {
        $this->getJson(route('cart.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function it_shows_products_in_the_user_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create()
        );

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'id' => $product->id
        ]);
    }

    /** @test */
    function it_shows_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'empty' => true
        ]);
    }

    /** @test */
    function it_shows_a_formatted_subtotal()
    {
        $user = factory(User::class)->create();

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'subtotal' => '$0.00'
        ]);
    }

    /** @test */
    function it_shows_a_formatted_total()
    {
        $user = factory(User::class)->create();

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'total' => '$0.00'
        ]);
    }

    /** @test */
    function it_shows_a_formatted_total_with_shipping()
    {
        $user = factory(User::class)->create();

        $shipping = factory(ShippingMethod::class)->create([
            'price' => 1000
        ]);

        $response = $this->signIn($user)->getJson(route('cart.index', ['shipping_method_id' => $shipping->id]));

        $response->assertJsonFragment([
            'total' => '$10.00'
        ]);
    }

    /** @test */
    function it_syncs_the_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $response = $this->signIn($user)->getJson(route('cart.index'));

        $response->assertJsonFragment([
            'changed' => true
        ]);
    }
}
