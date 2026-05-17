<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\InteractionAvgRating;
use Modules\Tasks\Models\User; // Assuming you have a User factory
use Modules\Tasks\Models\User as ReviewUser;

class InteractionAvgRatingFactory extends Factory
{
    protected $model = InteractionAvgRating::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Assuming you have a User factory
            'subject_type' => $this->faker->randomElement([ReviewUser::class, 'SomeOtherModel']), // Simulate the subject type
            'subject_id' => $this->faker->numberBetween(1, 50), // Simulate subject id (could be a related model's ID)
            'rating' => $this->faker->numberBetween(1, 5), // Simulate a rating between 1 and 5
        ];
    }
}
