<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Importer Builder
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importer BelongsTo
use Illuminate\Database\Eloquent\Relations\HasManyThrough; // Importer HasManyThrough
use Illuminate\Database\Eloquent\Relations\HasOneThrough; // Importer HasOneThrough
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\Hires;
use Modules\Tasks\Contracts\MatchInterface;
use DataSDK\Tools\Traits\Language;
use Modules\Calendar\Models\Calendar;


class Matches extends Model implements MatchInterface
{
    use Language;

    protected $table = "task_matches";

    public $translatable = [
        'name',
        'resume',
        'description'
    ];

    protected $fillable = [
        "uid",
        "task_id",
        "match_with_task_id",
    ];


 

    /**
     * Implement hire method from MatchInterface
     *
     * @return $this
     */
    public function hire()
    {
        // Implement the hire functionality
        $this->update([
            "hired" => 1,
            "rejected" => 0
        ]);

        return $this;
    }

    /**
     * Implement reject method from MatchInterface
     *
     * @return $this
     */
    public function reject()
    {
        // Implement the reject functionality
        $this->update([
            "hired" => 0,
            "rejected" => 1
        ]);

        return $this;
    }

    /**
     * Scope to hide rejected matches.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHideRejected(Builder $query): Builder
    {
        return $query->where('rejected', 0);
    }

    /**
     * Scope to hide hired matches.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHideHired(Builder $query): Builder
    {
        return $query->where('hired', 0);
    }

    /**
     * Get the company associated with the match.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, "task_id");
    }

    /**
     * Get the task associated with the match.
     *
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, "task_id");
    }

    /**
     * Get the matched task.
     *
     * @return BelongsTo
     */
    public function matchedTask(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, "match_with_task_id");
    }

    /**
     * Get the hires associated with the match.
     *
     * @return HasManyThrough
     */
    public function hires()
    {
        return $this->hasManyThrough(Tasks::class, Hires::class, "task_id", "id", "task_id", "hired_task_id");
    }

    /**
     * Get the hired task.
     *
     * @return HasOneThrough
     */
    public function hired()
    {
        return $this->hasOneThrough(Tasks::class, Hires::class, "task_id", "id", "task_id", "hired_task_id");
    }

    
    public function calendar(){

        return $this->hasMany(Calendar::class, "task_id", "id");

    }

    /**
     * Scope to filter matches for the current user.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeMe(Builder $query): Builder
    {
        $user_id = request()->user()->id;

        return $query->whereHas('task', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        });
    }

    /**
     * Scope to filter matches for other users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOther(Builder $query): Builder
    {
        $user_id = request()->user()->id;

        return $query->whereHas('task', function ($q) use ($user_id) {
            $q->where('user_id', '!=', $user_id);
        });
    }




}
