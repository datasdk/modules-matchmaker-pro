<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\TasksSkills;
use Modules\Tasks\Models\Tasks;
use DataSDK\Categories\Models\Categories;

class TasksSkillsFactory extends Factory
{
    protected $model = TasksSkills::class;

    public function definition()
    {
        return [
            'task_id' => Tasks::factory(), // Assuming you have a Tasks factory
            'category_id' => Categories::factory(), // Assuming you have a Categories factory
        ];
    }
}
