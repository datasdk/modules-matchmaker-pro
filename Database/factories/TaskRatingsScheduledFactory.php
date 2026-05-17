<?php

namespace Modules\Tasks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\User; // Tilpas til din User-model namespace

class TaskRatingsScheduledFactory extends Factory
{
    protected $model = TaskRatingsScheduled::class;

    public function definition()
    {
        return [
            'match_id' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
            'task_id' => Tasks::factory(),
            'task_for_rate_id' => Tasks::factory(),
        ];
    }
}
