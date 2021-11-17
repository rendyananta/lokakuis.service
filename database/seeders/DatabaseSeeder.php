<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Section;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)
            ->has(
                    Topic::factory()
                        ->has(
                            Section::factory()->has(
                                    Quiz::factory()->state(fn ($attributes, Section $section) => ['topic_id' => $section->getAttribute('topic_id')])->count(3)
                                )->count(2)
                            )
                        ->count(2)
                )
            ->create();
    }
}
