<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\TasksSpecifications;

class TasksSpecificationsFactory extends Factory
{
    protected $model = TasksSpecifications::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_id' => \Modules\Tasks\Models\Tasks::factory(), // Assuming you have a Task factory
            'specification' => $this->faker->word(),            // Random word for the specification name
            'value' => $this->faker->sentence(),                // Random sentence for the specification value
        ];
    }
}
