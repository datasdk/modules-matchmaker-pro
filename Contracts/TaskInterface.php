<?php

namespace Modules\Tasks\Contracts;

use Modules\Tasks\Models\Tasks;

interface TaskInterface
{
    public function user();
    public function users();
    public function skills();
    public function set_skills($skills);
    public function isJob();
    public function isApplication();
    public function set_company($company_id);
    public function set_user($user_id);
    public function set_uid($uid = null, $length = 40);
    
    public function like(Tasks $task, $like = 1, $shouldSplit = 0, $shouldReduce = 0);
    public function dislike(Tasks $task);
    public function isLiking(Tasks $task);
    public function isDisLiking(Tasks $task);
    public function removeMatch(Tasks $task);

    public function matches();
    public function match();
    public function myMatch();
    public function myMatches();
    public function matchesPivot();

    public function parentMatch();
    public function chats();
    public function chat();

    public function hires();
    public function hasHired(Tasks $task);
    public function isHiredBy(Tasks $task);
    public function hiredBy();

    public function interaction();
    public function interactions();
    public function myInteractions();
    public function likes();
    public function dislikes();
    public function likedBy();
    public function dislikedBy();

    public function setStatus($status);

    public function shouldSplit();
    public function shouldReduce();
}
