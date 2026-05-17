<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Modules\Tasks\Models\Companies;
use Illuminate\Support\Carbon;




trait TaskScopes
{

    public function scopeHasMatches($query)
    {

        return $query->has('matches');

    }


    public function scopeWithoutInteractionFrom($query, $task_id)
    {

        return $query->whereDoesntHave("interactions", function ($q) use ($task_id) {

            $q->where("task_id", $task_id);

        });

    }


    public function scopeWithInteractionFrom($query, $task_id)
    {

        return $query->whereHas("interactions", function ($q) use ($task_id) {

            $q->where("task_id", $task_id);

        });

    }


    public function scopeFromCompany($query, ...$arg){

        return $query->where("company_id",$arg[0]);

    }

    
    public function scopeIsLikedBy($query, $task_id)
    {

        return $query->whereHas("likedBy", function ($q) use ($task_id) {

            $q->where("task_id", $task_id);

        });

    }


    // I din Company model, fx: app/Models/Company.php

    public function scopeOnlyCompanyWithSubsidiaries($query, $companyId, $includeSubsidiaries = true)
    {
        $company = Companies::find($companyId);

        if($company){

            $ids = [$companyId]; // inkluderer altid hovedselskabet

            if ($includeSubsidiaries) {
                $subsidiaryIds = $company->subsidiaries()->pluck('id')->toArray();
                $ids = array_merge($ids, $subsidiaryIds);
            }

            return $query->whereIn('company_id', $ids);

            
        } else {

            return $query->whereRaw('1 = 0');

        }
        
    }



    public function scopeHasLiked($query, $task_id)
    {

        return $query->whereHas("likes", function ($q) use ($task_id) {

            $q->where("likeable_task_id","!=", 111111111);

        });

    }


    public function scopeWithMatchesFromUser($query, $user_id, $strict = true){

        return $query->whereHas("matches", function ($q) use ($user_id,$strict) {

            if($strict){
                
                $q->where("user_id", $user_id);

            } else {
                
                $q->orWhere("user_id", $user_id);

            }
            

        });

    }


    public function scopeWithoutFromUserId($query, $user_ids)
    {
        return $query->whereNotIn("user_id", (array) $user_ids);
    }

    public function scopeWhereLive($query)
    {
        return $query->where("status", "live");
    }


    /**
     * Scope: Finder potentielle matches baseret på søgekriterier.
     *
     * Mulige parametre i $searchOptions:
     * - type (string): "job" eller "application" (bestemmer, hvad man søger efter).
     * - categories (array): Liste af kategori-ID'er.
     * - price (int): Maks/min pris afhængigt af søgetype.
     * - available (array):
     *      - from (date): Startdato for tilgængelighed.
     *      - to (date): Slutdato for tilgængelighed.
     * - position (array) [valgfri]:
     *      - lat (float): Latitude-koordinat.
     *      - lng (float): Longitude-koordinat.
     *      - radius (int): Radius i meter.
     * - tags (array) [valgfri]: Liste af tags.
     * - sorting (string) [valgfri]: Sorteringsmetode ("favorite", "rating", "start_date", "end_date").
     *
     * @param Builder $query
     * @param array $searchOptions
     * @param int $userId
     * @return Builder
     */

    public function scopeSearchMatches(Builder $query, array $searchOptions = [])
    {
        $taskType = $searchOptions['type'] ?? null;
        $searchType = ($taskType === "job") ? "application" : "job";
        $userId = $searchOptions['user_id'] ?? auth()->id;
        $iamJob = ($searchType === "application"); // Hvis man søger job, er man en application
        $priceOperator = $iamJob ? ">=" : "<=";

        // Grundlæggende filtre
        $query->where('type', $searchType)
            ->where('user_id', '!=', $userId)
            ->where('status', 'live');

          // Tilføj kategorifilter, hvis sat
          if (isset($searchOptions['categories'])) {
            $query->whereHas('categories', function ($q) use ($searchOptions) {
                $q->whereIn('id', $searchOptions['categories']);
            });
        }

        // Tilføj prisfilter, hvis sat
        if (isset($searchOptions['price'])) {
            $query->where('price', $priceOperator, $searchOptions['price']);
        }

        // Tilføj datointervaller, hvis sat
        if (isset($searchOptions['available']['from'], $searchOptions['available']['to'])) {
            $query->whereHas('available', function ($q) use ($searchOptions) {
                $q->whereBetween('from', [
                    $searchOptions['available']['from'],
                    $searchOptions['available']['to']
                ]);
            });
        }


        // Filtrer på afstand, hvis position er angivet
        if (isset($searchOptions['position']['lat'], $searchOptions['position']['lng'], $searchOptions['position']['radius'])) {
            
            $pos = $searchOptions['position'];

            $query->WithinDistanceOfAddress($pos['lng'], $pos['lat'], $pos['radius']);            
           
        }

        // Filtrer efter tags, hvis det er et job, og tags er sat
        if ($iamJob && isset($searchOptions['tags'])) {
            $query->whereHas('tags', function ($q) use ($searchOptions) {
                $q->whereIn('name', $searchOptions['tags'])
                ->where("group_name","search");
            });
        }

        // Sortering
        if (isset($searchOptions['sorting'])) {
            switch ($searchOptions['sorting']) {
                case "favorite":
                    $query->with('favorites')->orderByDesc('favorites_count');
                    break;
                case "rating":
                    $query->orderBy('userAvgRating.rating', 'asc');
                    break;
                case "start_date":
                    $query->orderBy('available_from', 'asc');
                    break;
                case "end_date":
                    $query->orderBy('available_to', 'asc');
                    break;
            }
        }

        // Standard sortering: Nyeste opgaver først
        return $query->orderBy('created_at', 'desc');

    }


    public function scopeWithoutSplittedTasks($query)
    {
        return $query->doesntHave('children'); 
    }



    public function scopeWithSplittedTasks($query)
    {
        return $query->whereHas('children'); 
    }


    public function scopeAmount(Builder $query, string $operators, int $value)
    {
        return $query->where('amount',$operators,$value); 
    }





    public function scopeIsOverlapping(Builder $query, string $start, string $end)
    {

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        return $query->whereHas('available', function ($q) use ($start, $end) {

            
            $q->where(function ($q2) use ($start, $end) {
               // 1. from <= start AND to >= end
                $q2->whereDate('from', '<=', $start)
                ->whereDate('to', '>=', $end);
            })
            ->orWhere(function ($q2) use ($start, $end) {
            
                // 2. from >= start AND to >= end
                $q2->whereDate('from', '>=', $start)
                ->whereDate('from', '<=', $end);

            })
            ->orWhere(function ($q2) use ($start, $end) {
                // 3. from <= start AND to >= start
                $q2->whereDate('from', '<=', $start)
                ->whereDate('to', '>=', $start);
            })
             ->orWhere(function ($q2) use ($start, $end) {
                // 4. from >= start AND to <= end
                $q2->whereDate('from', '>=', $start)
                ->whereDate('to', '<=', $end);
            })
            ;

        });


    }

    
}
