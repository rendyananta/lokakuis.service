<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $image = $this->faker->image(null, 360, 360, 'animals', true);
        $storage = Storage::disk(config('filesystems.default'));

        $path = $storage->put('topics/quiz', new File($image));

        return [
            'question' => $this->faker->text(100),
            'answer' => $this->faker->text(400),
            'image' => $path
        ];
    }
}
