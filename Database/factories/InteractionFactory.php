<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\TaskInteractions;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\User;

class InteractionFactory extends Factory
{
    protected $model = TaskInteractions::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Assuming you have a User factory
            'task_id' => Tasks::factory(), // Assuming you have a Tasks factory
            'likeable_task_id' => Tasks::factory(), // Assuming you have a Tasks factory
            'like' => $this->faker->boolean(), // Randomly generates a true/false value for 'like'
        ];
    }
}
