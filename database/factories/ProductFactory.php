<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;


class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */


     protected $model = Product::class;

    public function definition()
    {
        return [
            //
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(100, 1000),
            'image' => $this->faker->imageUrl(300, 300),
            'category' => $this->faker->word,
            'subcategory' => $this->faker->word,
            'brand' => $this->faker->word,
            'vendor' => $this->faker->word,

        ];
    }
}
