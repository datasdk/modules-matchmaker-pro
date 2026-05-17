<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Modules\Tasks\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskMatch;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Database\Seeders\TasksDatabaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Modules\Email\Models\Email;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Modules\Email\Traits\TestsEmailTrait;

        

class TaskMatchTest extends TestExtend
{

    use RefreshDatabase;
    use TestsEmailTrait;


    protected function setUp(): void
    {
        parent::setUp();


        Artisan::call("db:seed",[
            "--class" => CountrySeeder::class
        ]);

        Artisan::call("module:seed tasks");

        $this->makeMatch();

    }



    public function test_tasks_can_match_and_hire()
    {
         
        $this->assertTrue($this->isMatching, "Error: Matching tasks, does not match");

        $isMatching = app(MatchService::class)->isMatching($this->job,$this->application);

        $this->assertTrue($isMatching, "Error: Matching tasks, does not match");


   
        $hasHired = $this->job->hasHired($this->application);

        $this->assertTrue($hasHired, "Error: Job has not hired application");

   
        $isHired = $this->application->isHiredBy($this->job);


        $this->assertTrue($isHired, "Error: application is not hired by job");


    

        foreach([$this->job, $this->application] as $task){
            
            $this->assertTrue($task->address->street != null, "Error: ".$task->type." dosent have address");

            $this->assertTrue($task->contact->email != null, "Error: ".$task->type." dosent have contact");

            $this->assertTrue($task->available->from != null, "Error: ".$task->type." dosent have availablility");

        }

    }


    public function test_email_is_sent(){

        $user = $this->user;

        $user2 = $this->user2;


        $emails = [
            Email::where("user_id",$user->id)->firstOrFail(),
            Email::where("user_id",$user2->id)->firstOrFail()
        ];

    
        $this->assertTrue(Email::count() > 0, "Error: E-mail was not sent");


        foreach($emails as $email){

    
            $hasAttachments = $email->getMedia("attachments")->count() > 0;

            $this->assertTrue($hasAttachments, "Error: E-mail does not contain any attachments");


            $this->assertEmailHasNoTemplateMarkers($email);


        }


    }

}
