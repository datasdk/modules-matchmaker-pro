<?php

namespace Modules\Tasks\Console\Commands;

use Illuminate\Console\Command;
use Modules\Tasks\Models\Categories; // Brug den korrekte model

class AddMissingCounters extends Command
{
    protected $signature = 'tasks:counters:add-missing';
    protected $description = 'Tilføj "usage"-counter til alle kategorier, der mangler den';

    public function handle()
    {
        $this->info("Starter...");

        $categories = Categories::all();
        $count = 0;

        foreach ($categories as $category) {
            if (!$category->hasCounter("usage")) {
                $category->addCounter("usage");
                $count++;
            }
        }

        $this->info("Tilføjede 'usage' counter til {$count} kategorier.");
    }
}
