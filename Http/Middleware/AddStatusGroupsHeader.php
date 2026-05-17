<?php

namespace Modules\Tasks\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tasks\Models\Tasks;
use Illuminate\Support\Facades\DB;
use Throwable;

class AddStatusGroupsHeader
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = $request->user();

        if ($user) {

            $showTaskFromUserId = $request->user_id ?? $user->id;
            $showAll = $showTaskFromUserId === "all";

            try {
                $query = Tasks::query();

                if (!$showAll) {
                    $query->where('user_id', (int) $showTaskFromUserId);
                }

                $results = $query
                    ->select('type', 'status', DB::raw('COUNT(*) as total'))
                    ->whereNotIn('status', ["splitted"])
                    ->groupBy('type', 'status')
                    ->get();

                $total = $results->sum('total');

                $statusGroups = $results
                    ->groupBy('type')
                    ->map(function ($group) {
                        return $group->pluck('total', 'status');
                    })
                    ->toArray();

                $statusGroups["all"] = $total;

                // Brug helper i stedet for repository
                set_custom_header('taskStatusGroups', $statusGroups);

            } catch (Throwable $e) {
                \Log::warning('Fejl i AddStatusGroupsHeader: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
