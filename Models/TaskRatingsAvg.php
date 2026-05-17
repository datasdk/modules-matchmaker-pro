<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Tasks\Contracts\TaskRatingsAvgInterface;

class TaskRatingsAvg extends Model implements TaskRatingsAvgInterface
{
    use HasFactory;

    protected $table = 'task_ratings_avg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'subject_type',
        'subject_id',
        'rating',
    ];
    public $timestamps = true;
    protected $casts = [
        'rating' => 'float',
    ];

    /**
     * Update or create the interaction average rating for a specific subject.
     *
     * @param  string  $subjectType
     * @param  int  $subjectId
     * @param  float  $averageRating
     * @return TaskRatingsAvg
     */
    public static function updateOrCreateRating(string $subjectType, int $subjectId, float $averageRating)
    {
        return self::updateOrCreate(
            [
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
            ],
            [
                'rating' => round($averageRating, 1),
            ]
        );
    }

    /**
     * Find the average rating for a specific subject.
     *
     * @param  Model  $model
     * @return float|null
     */
    public static function findAvgRating(Model $model): ?float
    {
        return round(self::where('subject_type', get_class($model))
            ->where('subject_id', $model->id)
            ->avg('rating'), 1); // Use avg() to calculate the average
    }
}
