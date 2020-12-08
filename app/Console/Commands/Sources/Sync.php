<?php

namespace App\Console\Commands\Sources;

use DateTime;
use DB;
use App\Console\Commands\Command;
use App\Models\Source;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs all sources';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sources = json_decode(file_get_contents(base_path().'/resources/assets/sources.json'), true);
        
        foreach ($sources as $newData) {

            $source = Source::where([
                'name' => $newData['name'],
                'full_name' => $newData['full_name'],
                'active' => $newData['active'],
            ])->first();

            if (!$source) {
                $this->info(sprintf('Configuring source %s...', $newData['name']));

                $source = Source::create([
                    'name'      => $newData['name'],
                    'full_name'      => $newData['full_name'],
                    'active' => $newData['active'],
                ]);

                $progress = $this->output->createProgressBar(5);
            } else {
                $this->info(sprintf('Updating source %s...', $newData['name']));

                $source->name = $newData['name'];
                $source->full_name = $newData['full_name'];
                $source->active = $newData['active'];
                $source->save();

                $progress = $this->output->createProgressBar(4);
            }

            $progress->advance();
            $progress->finish();

            $this->info("\n\n");
        }

        return;
    }
}
