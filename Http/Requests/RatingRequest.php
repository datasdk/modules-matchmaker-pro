<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;

class RatingRequest extends Request
{
    // Validation rules for storing a rating
    public function storeRules(): array
    {

        $rules = [
            'task_id' => 'required|exists:tasks,id',
            'task_for_rate_id' => 'required|exists:tasks,id',
        ];


        if ($this->has('rating')) {

            $rules['rating'] = 'required_without:ratings|int';

        } else {

            $rules['ratings'] = 'required_without:rating|array|min:1';

            $rules['ratings.*.category'] = 'required|string|max:255';

            $rules['ratings.*.stars'] = 'required|integer|min:1|max:5';

        }

        return $rules;

    }


    // Validation rules for updating a rating
    public function updateRules(): array
    {
        $rules = [];

        if ($this->has('rating')) {

            $rules['rating'] = 'required_without:ratings|int';

        } else {

            $rules['ratings'] = 'required_without:rating|array|min:1';

            $rules['ratings.*.category'] = 'required|string|max:255';

            $rules['ratings.*.stars'] = 'required|integer|min:1|max:5';

        }


        return $rules;

    }


    // Custom error messages for validation failures
    public function messages(): array
    {

        return [
            // Task IDs
            'task_id.required' => 'Task ID er påkrævet.',
            'task_id.exists' => 'Den valgte opgave findes ikke.',
            'task_for_rate_id.required' => 'Hired Task ID er påkrævet.',
            'task_for_rate_id.exists' => 'Den valgte ansættelsesopgave findes ikke.',

            // rating (int)
            'rating.required_without' => 'Du skal angive enten en samlet vurdering (rating) eller detaljerede vurderinger (ratings).',
            'rating.int' => 'Rating skal være et heltal.',

            // ratings (array)
            'ratings.required_without' => 'Du skal angive enten detaljerede vurderinger (ratings) eller en samlet vurdering (rating).',
            'ratings.array' => 'Ratings skal være et array.',
            'ratings.min' => 'Der skal angives mindst én vurdering.',

            // ratings.*.category
            'ratings.*.category.required' => 'Kategori for hver vurdering er påkrævet.',
            'ratings.*.category.string' => 'Kategori skal være en tekst.',
            'ratings.*.category.max' => 'Kategori må ikke være længere end 255 tegn.',

            // ratings.*.stars
            'ratings.*.stars.required' => 'Angiv venligst din vurdering (stjerner) for alle kriterier.',
            'ratings.*.stars.integer' => 'Stjerner skal være et heltal.',
            'ratings.*.stars.min' => 'Stjerner skal være mindst 1.',
            'ratings.*.stars.max' => 'Stjerner må ikke være højere end 5.',
        ];

    }

}
