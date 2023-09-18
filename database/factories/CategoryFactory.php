<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = ['en', 'ar'];
        $translations = collect($locales)->mapWithKeys(function($locale) {
            return [
                $locale => [
                    'name' => fake()->name,
                ]
            ];
        })->toArray();
    
        return array_merge($translations);
    
    }
}
