<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\User;
use Modules\Companies\Models\Companies;
use Faker\Generator as Faker;
use Lecturize\Addresses\Models\Address;
use Modules\Tasks\Models\Categories;


class TasksFactory extends Factory
{

    protected $model = Tasks::class;


    public function definition()
    {

        return [
            'type' => $this->faker->randomElement(['job', 'project']),
            'name' => $this->faker->jobTitle,
            'slug' => $this->faker->slug,
            'resume' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'label' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'link' => $this->faker->url,
            'access' => $this->faker->boolean,
            'amount' => $this->faker->randomNumber(1,100),
            'sorting' => $this->faker->randomNumber(),
            'settings' => json_encode(['setting1' => $this->faker->word]), // Example of storing settings as JSON
            'active' => $this->faker->boolean,
        ];

    }


    public function configure(): static
    {

        return $this->afterCreating(function (Tasks $task) {
            

            $task->setAddress([
                'street'    => $this->faker->streetAddress,
                'city'      => $this->faker->city,
                'state'     => $this->faker->state,
                'post_code' => $this->faker->postcode,
                'country_id' => $this->faker->randomNumber(1, false), // Assuming country_id is a numeric reference
                'note'      => $this->faker->sentence,
                'lat'       => $this->faker->latitude,
                'lng'       => $this->faker->longitude,
                'is_primary'=> $this->faker->boolean,
                'is_billing'=> $this->faker->boolean,
                'is_shipping'=> $this->faker->boolean,
            ]);

            

            // Optionally, you can create a second address if needed
            $task->setContact([
                'type'         => 'default', // Eller evt. $this->faker->randomElement(['default', 'billing', 'shipping'])
                'first_name'   => $this->faker->firstName,
                'middle_name'  => $this->faker->firstName,
                'last_name'    => $this->faker->lastName,
                'company'      => $this->faker->company,
                'vat_id'       => $this->faker->numberBetween(9999999, 99999999),
                'position'     => $this->faker->jobTitle,
                'phone'        => $this->faker->phoneNumber,
                'mobile'       => $this->faker->phoneNumber,
                'fax'          => $this->faker->phoneNumber,
                'email'        => $this->faker->safeEmail,
                'website'      => $this->faker->url,
                'notes'        => $this->faker->paragraph,
            ]);


        
            $task->set_available([
                'from' => $this->faker->dateTimeBetween('now', '+12 week')->format('Y-m-d'),
                'to'   => $this->faker->dateTimeBetween('+12 week', '+12 month')->format('Y-m-d'),
            ]);
            
      

            $company = Companies::factory()->create();
            $task->set_company($company->id);

       

            $category = Categories::factory()->create();
            $task->setCategories($category->id);


            
            if(!$task->user_id){

                $randomId = rand(1, User::count());
                $task->set_user($randomId);

            }
            

            
            $task->refresh();


        });


    } 
  
}
