<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'warranty_id'=>1,
            'unit_id'=>1,
            'stock'=>$this->faker->randomNumber(1),
            'price'=>$this->faker->randomFloat(3,0,1000),
            'description'=>$this->faker->text(),
            'name'=>$this->faker->regexify('[A-Za-z0-9]{30}'),
            'slug'=>$this->faker->slug(),
            'category_id'=>8
        ];
    }
}
