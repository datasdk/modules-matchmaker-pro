<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\Hires;
use Modules\Tasks\Models\Tasks;

class HiresFactory extends Factory
{
    protected $model = Hires::class;

    public function definition()
    {
        return [
            'task_id' => Tasks::factory(), // Assuming you have a Tasks factory
            'hired_task_id' => Tasks::factory(), // Assuming you have a Tasks factory
        ];
    }
}
