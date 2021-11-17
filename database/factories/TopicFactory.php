<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $banner = $this->faker->image(null, 360, 360, 'animals', true);
        $storage = Storage::disk(config('filesystems.default'));

        $path = $storage->put('topics', new File($banner));

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'banner' => $path,
            'is_public' => true
        ];
    }
}
