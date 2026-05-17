<?php

namespace Modules\Tasks\Database\factories;

use Modules\Tasks\Models\Tasks;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\Matches;

class MatchesFactory extends Factory
{
    protected $model = Matches::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uid' => $this->faker->unique()->uuid, // Unique match identifier
            'task_id' => Tasks::inRandomOrder()->first()->id, // Random task_id from existing tasks
            'match_with_task_id' => Tasks::inRandomOrder()->first()->id, // Random matched task_id
            // You can add other fields as needed, for example:
            // 'name' => $this->faker->word,
            // 'resume' => $this->faker->sentence,
            // 'description' => $this->faker->paragraph,
        ];
    }
}
