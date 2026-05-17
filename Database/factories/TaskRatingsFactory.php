<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Models\Tasks;
use App\Models\User; // Sørg for at denne import matcher din User-model

class TaskRatingsFactory extends Factory
{
    protected $model = TaskRatings::class;

    public function definition(): array
    {
        return [
            'rater_id' => Tasks::factory(["type" => "job"]),
            'rater_type' => Tasks::class,
            'target_id' => Tasks::factory(["type" => "application"]),
            'target_type' => Tasks::class,
            'user_id' => null, // Hvis null, bliver den sat i configure()
            'stars' => $this->faker->numberBetween(1, 5),
            'type' => $this->faker->randomElement(['job', 'applicant']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TaskRatings $rating) {
            if (!$rating->task_id) {
                $task = Tasks::factory()->create();
                $rating->task_id = $task->id;
            }

            if (!$rating->user_id) {
                $user = \App\Models\User::factory()->create();
                $rating->user_id = $user->id;
            }

            $rating->save();
        });
    }
}
