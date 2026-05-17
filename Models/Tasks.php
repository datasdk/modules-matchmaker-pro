<?php

namespace Modules\Tasks\Models;

use ActionModel;
use App\Models\User as BaseUser;
use Modules\Tasks\Models\User;
use Modules\Tasks\Models\TasksSkills;
use DataSDK\Categories\Models\Categories;


use Modules\Tasks\StateMachines\StatusStateMachine;
use Modules\Tasks\Traits\CanBeRated;
use Modules\Tasks\Models\TaskInteractions as Interaction;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Models\Tasks;


use Modules\Tasks\Models\TaskRatingsAvg;
use Modules\Tasks\Models\Companies;
use Modules\Companies\Models\Companies as OrigCompanies;
use DataSDK\Addresses\Traits\HasAddresses;
use DataSDK\Addresses\Traits\HasContacts;
use Modules\Tasks\Http\Scopes\OrderByFavorites;
use Spatie\Tags\HasTags;
use Modules\Tasks\Models\Hires;
use Illuminate\Support\Str;
use Modules\Tasks\Http\Scopes\OnlyHired;
//use Modules\Tasks\Http\Scopes\FilterMatches;
use Modules\Chat\Models\Conversation as Chats;
use Modules\Tasks\Models\HiresPivot;
use Modules\Tasks\Http\Scopes\MyMatches;

use Modules\Tasks\Http\Scopes\TaskScopes;
use Modules\Media\Contracts\HasMedia;
use Modules\Media\Traits\InteractsWithMedia;

use Modules\Tasks\Http\Scopes\CalendarTasks;
use Turahe\Counters\Models\Counter;
use Modules\Tasks\Traits\HasCounter;
Use DataSDK\Tools\Traits\Nestable;
use Modules\Tasks\Http\Scopes\WithoutHired;
use Modules\Tasks\Events\MatchCreated;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Contracts\TaskInterface;
use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Cron\Traits\HasCronJob;


class Tasks extends ActionModel implements TaskInterface, HasMedia
{

    //FilterMatches
    use CanBeRated, HasTags;
    use MyMatches, OnlyHired, OrderByFavorites, WithoutHired;
    use HasAddresses, HasContacts, Nestable;
    use TaskScopes, CalendarTasks;
    use InteractsWithMedia;
    use HasCronJob;
    use HasCounter;
    

    public $translatable = ['name', 'slug', 'resume', 'description'];
    
    public $sluggable = 'name';

    public $appends = [
        "matches_count", 
        "hires_count", 
        "averageRating", 
        "is_splitted",
        "reactions",
        "hire",
        //"averageRatingAllTypes", 
      //  "tags", 
        "matchId",
        "attatchments"
    ];



   
    
    protected $fillable = [
         "type", "name", 'case_number', 'slug', "resume", "description", "label", "price", "link", "access", "amount",
        "user_id", "company_id", "sorting", "settings", "settings", "search_distance","active","status","parent_id","potential_matches"
    ];


    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($task) {
            
            if($task->hasCounter('views')){

                $task->incrementCounter('views');

            }
            

        });

    }


  

    public function getAttatchmentsAttribute()
    {
    
        return $this->getAllMedia();

    }

  

    public function getMatchIdAttribute(){

        $matchService = app(MatchService::class);

        $laravel_through_key = $this->laravel_through_key;

        $id = $this->id;

        $task = Tasks::find($id);
        $task2 = Tasks::find($laravel_through_key);
        
        if($matchService->isMatching($task ,$task2)){
            
            return $matchService->makeMatchId($task->id, $task2->id);

        }

        return null;
        
    }
    

    private function getTaskByRequest(){

        return Tasks::find($this->laravel_through_key);

    }

 

    public function getHireAttribute(){

        $task = $this->getTaskByRequest();

        if(!$task){ return null; }
        
        $has_hired = $this->hasHired($task);

        $hired_by_me = $this->isHiredBy($task);

        return [
            "has_hired" => $has_hired,
            "hired_by_me" => $hired_by_me,
            "complete" => $has_hired || $hired_by_me
        ];

    }


    public function getReactionsAttribute(){

        // root task
        $task = $this->getTaskByRequest();
 
        if(!$task){ return null; }


        $likes_me = $this->isLiking($task);

        $dislike_me = $this->isDisLiking($task);

        $likes = $task->isLiking($this);

        $dislikes = $task->isDisLiking($this);


        return [
            "interaction" => $likes || $dislikes,
            "interaction_with_me" => $likes_me || $dislike_me,
            "likes" => $likes,
            "dislikes" => $dislikes,
            "likes_me" => $likes_me,
            "dislike_me" => $dislike_me
        ];

    }





    public function getMatchesCountAttribute(){
        return $this->matches()->count();
    }

    public function getHiresCountAttribute(){
        return $this->hires()->count();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->morphedByMany(User::class, 'userable', 'tasks_users', 'task_id');
    }

    public function skills(){
        return $this->belongsToMany(Categories::class, "categories_models", "model_id", "category_id");
    }





    public function set_skills($skills){
        $this->setCategories($skills);
        return $this;
    }

    public function isJob(){
        return $this->type === "job";
    }

    public function isApplication(){
        return $this->type === "application";
    }

    public function set_company($company_id){
        if($company_id){
            $this->update(['company_id' => $company_id]);
        }
        return $this;
    }

    public function set_user($user_id){
        $this->update(['user_id' => $user_id]);
        return $this;
    }

    public function set_uid($uid = null, $length = 40){
        $this->update(['uid' => $uid ?: Str::random($length)]);
        return $this;
    }



    public function like(Tasks $task, $like = 1, $shouldSplit = 0, $shouldReduce = 0){


    
        $attributes = [
            'user_id' => auth()->id(),
            'task_id' => $this->id,
            'likeable_task_id' => $task->id,
            
        ];


        $extraAttributes = [
            'like' => intval($like)
        ];


        if(!is_null($shouldSplit)){

            $extraAttributes['should_split'] = intval($shouldSplit);

        }

        if(!is_null($shouldReduce)){

            $extraAttributes['should_reduce'] = intval($shouldReduce);

        }

        
        return Interaction::updateOrCreate(
            $attributes,
            $extraAttributes
        );
        
    }


    public function isLiking(Tasks $task){
        
        return Interaction::query()
            ->where(['task_id' => $this->id, 'likeable_task_id' => $task->id, 'like' => 1])
            ->exists();

    }


    public function isDisLiking(Tasks $task){
        
        return Interaction::query()
            ->where(['task_id' => $this->id, 'likeable_task_id' => $task->id, 'like' => 0])
            ->exists();

    }
 

    public function removeMatch(Tasks $task){
        // Implementation missing
    }




    
    public function dislike(Tasks $task){
        return $this->like($task, false);
    }

    public function matches(){
        return $this->hasManyThrough(Tasks::class, Matches::class, "match_with_task_id", "id", "id", "task_id");
    }

    public function match(){
        return $this->hasOneThrough(Tasks::class, Matches::class, "match_with_task_id", "id", "id", "task_id");
    }

    public function myMatch(){
        
        return $this->match()->where("user_id",optional(auth()->user())->id);

    }

    public function myMatches(){
        
        return $this->matches()->where("user_id",optional(auth()->user())->id);

    }

    public function matchesPivot(){
        return $this->hasMany(Matches::class, "match_with_task_id", "id");
    }

    public function parentMatch(){
        
        return $this->hasOneThrough(Tasks::class, Matches::class, "task_id", "id", "id", "match_with_task_id");

    }

    public function calendar(){

        return $this->hasMany(Calendar::class, "task_id", "id");

    }


    public function other(){

        return $this->hasOne(Interaction::class, "likeable_task_id", "id");

    }

/*

sleet


    public function myInteractions()
    {
        return $this->belongsToMany(Tasks::class, 'tasks_interactions', 'user_id', 'task_id')
                    ->withPivot('like');
    }

*/

    public function type(){

        return $this->type;

    }

    public function applicant(){

        return $this->belongsTo(Tasks::class, "applicant_task_id");

    }

    public function company()
    {

        return $this->belongsTo(Companies::class, 'company_id', 'id');

    }


    public function companyRating()
    {
   
        return $this->hasOneThrough(
            TaskRatingsAvg::class, // Slutdestinationen (avgRating)
            OrigCompanies::class,      // Mellemste model (Company)
            'id',                  // Foreign key i Companies (company_id i Tasks peger her)
            'subject_id',          // Foreign key i TaskRatingsAvg (peger på company)
            'company_id',          // Foreign key i Tasks (peger på Companies)
            'id'                   // Primær nøgle i Companies (som TaskRatingsAvg.subject_id peger på)
        )->where('task_ratings_avg.subject_type', OrigCompanies::class);

    }


    public function userRating()
    {
   
        return $this->hasOneThrough(
            TaskRatingsAvg::class, // Slutdestinationen (avgRating)
            User::class,      // Mellemste model (Company)
            'id',                  // Foreign key i Companies (company_id i Tasks peger her)
            'subject_id',          // Foreign key i TaskRatingsAvg (peger på company)
            'user_id',          // Foreign key i Tasks (peger på Companies)
            'id'                   // Primær nøgle i Companies (som TaskRatingsAvg.subject_id peger på)
        )->where('task_ratings_avg.subject_type', BaseUser::class); // baseuse important!!

    }



    public function ratings()
    {

        return $this->hasMany(TaskRatings::class, "task_id", "id");

    }


    public function scheduledRatings()
    {

        return $this->hasMany(TaskRatingsScheduled::class, "task_id", "id");

    }
   

    public function chats(){

        return $this->hasManyThrough(Chats::class, Matches::class, "task_id", "name", "id", "uid");

    }

    public function chat(){

        return $this->hasOneThrough(Chats::class, Matches::class, "task_id", "name", "id", "uid");

    }


    public function hires(){

     
        return $this->hasManyThrough(
            Tasks::class, 
            HiresPivot::class, 
            "task_id", 
            "id", 
            "id", 
            "hired_task_id"
        );

    }

   
    
    public function hasHired(Tasks $task){

        return Hires::where("task_id",$this->id)->where("hired_task_id",$task->id)->exists();

    }

    public function isHiredBy(Tasks $task){

        return Hires::where("task_id",$task->id)->where("hired_task_id",$this->id)->exists();

    }


    public function info(){

        return $this->hasOne(Hires::class,"hired_task_id");

    }

    public function hiredBy(){

        return $this->hasManyThrough(Tasks::class, Hires::class, "hired_task_id", "id", "id", "task_id");

    }


    public function allApplicants(){

        return $this->hasManyThrough(User::class, Interaction::class, "task_id", "id", "id", "user_id");

    }

    public function interaction(){

        return $this->hasOne(Interaction::class, "task_id", "id");

    }

    public function interactions(){

        return $this->hasManyThrough(Tasks::class, Interaction::class, "likeable_task_id", "id", "id", "task_id");

    }

  

    public function myInteractions(){
        
        return $this->hasManyThrough(Tasks::class, Interaction::class, "task_id", "id", "id", "likeable_task_id");

    }
    

    public function likes()
    {

        return $this->myInteractions()->where("like",1); 

    }

  

    public function setStatus($status){

        $this->status = $status;

        $this->save();

        return $this;

    }
    

    public function dislikes()
    {

        return $this->myInteractions()->where("like",0); 

    }

    
    public function likedBy()
    {

        return $this->interactions()->where("like",1); 

    }


    public function dislikedBy()
    {

        return $this->interactions()->where("like",1); 

    }



    public function companyAvgRating()
    {

        return $this->company()->first()->morphOne(TaskRatingsAvg::class, 'subject');
    }

  
    public function isSplitted(){

        return $this->hasChildren();
    }


    public function getIsSplittedAttribute(){

        return $this->isSplitted();
    }


    public function shouldSplit()
    {
        
        return boolval(optional($this->interaction)->should_split);

    }

    public function shouldReduce()
    {
        
        return boolval(optional($this->interaction)->should_reduce);

    }

    public function shouldNone()
    {
        
        return !$this->shouldSplit() && !$this->shouldReduce();

    }

    public function isFullfilled(){

        if($task->isJob()){

            $hireAmount = $task->hires->sum('amount');
    
            if($task->amount <= $hireAmount){
    
                return true;
    
            }
    
        } else {

            $hireAmount = $task->hiredBy->sum('amount');
    
            if($task->amount <= $hireAmount){
    
                return true;
    
            }

        }

        return false;

    }

}
