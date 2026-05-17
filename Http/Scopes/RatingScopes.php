<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait RatingScopes
{
    /**
     * Scope: Find modeller med en minimum gennemsnitlig rating.
     */
    public function scopeWithMinimumRating(Builder $query, float $rating): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($rating) {
            $q->havingRaw('AVG(stars) >= ?', [$rating]);
        });
    }

    /**
     * Scope: Find modeller med præcis en given rating.
     */
    public function scopeWithExactRating(Builder $query, float $rating): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($rating) {
            $q->havingRaw('AVG(stars) = ?', [$rating]);
        });
    }

    /**
     * Scope: Find modeller med en rating inden for et interval.
     */
    public function scopeWithRatingBetween(Builder $query, float $minRating, float $maxRating): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($minRating, $maxRating) {
            $q->havingRaw('AVG(stars) BETWEEN ? AND ?', [$minRating, $maxRating]);
        });
    }

    /**
     * Scope: Find modeller, der er blevet rated af en bestemt bruger.
     */
    public function scopeRatedByUser(Builder $query, int $user_id): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($user_id) {
            $q->where('rater_id', $user_id);
        });
    }

    /**
     * Scope: Find modeller, der har fået mindst X antal ratings.
     */
    public function scopeWithMinimumRatingsCount(Builder $query, int $count): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($count) {
            $q->havingRaw('COUNT(*) >= ?', [$count]);
        });
    }

    /**
     * Scope: Find modeller, der er blevet rated af en bestemt type bruger (f.eks. "business" eller "private").
     */
    public function scopeRatedByUserType(Builder $query, string $type): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($type) {
            $q->whereHas('rater', function (Builder $userQuery) use ($type) {
                $userQuery->where('type', $type);
            });
        });
    }

    /**
     * Scope: Find modeller, hvor en bestemt bruger har givet en bestemt rating.
     */
    public function scopeRatedByUserWithStars(Builder $query, int $user_id, int $stars): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($user_id, $stars) {
            $q->where('rater_id', $user_id)->where('stars', $stars);
        });
    }

    /**
     * Scope: Find modeller, der aldrig er blevet rated.
     */
    public function scopeUnrated(Builder $query): Builder
    {
        return $query->whereDoesntHave('ratings');
    }

    /**
     * Scope: Find modeller med ratings oprettet inden for et bestemt antal dage.
     */
    public function scopeRecentlyRated(Builder $query, int $days = 30): Builder
    {
        return $query->whereHas('ratings', function (Builder $q) use ($days) {
            $q->where('created_at', '>=', now()->subDays($days));
        });
    }

    /**
     * Scope: Find modeller med den højeste gennemsnitlige rating (top X antal).
     */
    public function scopeTopRated(Builder $query, int $limit = 10): Builder
    {
        return $query->withCount(['ratings as average_rating' => function (Builder $q) {
            $q->selectRaw('AVG(stars)');
        }])->orderByDesc('average_rating')->limit($limit);
    }
}
