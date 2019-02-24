<?php

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Faker\Generator as Faker;

$factory->define(ProductVariationType::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->unique()->word),
    ];
});