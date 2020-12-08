<?php

namespace App\Console\Commands\Settings;

use DateTime;
use DB;
use App\Console\Commands\Command;
use App\Models\Source;
use App\Models\Setting;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs all settings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = json_decode(file_get_contents(base_path().'/resources/assets/configuration_form/settings.json'), true);

        foreach ($settings as $newData) {
            $source = Source::where('name', $newData['source_name'])->first();
            
            if (!$source) {
                $this->info(sprintf('Source %s does not exist', $newData['source_name']));
                continue;
            }

            $setting = Setting::where([
                'source_id' => $source->id,
                'key' => $newData['key'],
            ])->first();

            if (!$setting) {
                $this->info(sprintf('Configuring source %s, setting %s...', $newData['source_name'], $newData['key']));

                $setting = Setting::create([
                    'source_id' => $source->id,
                    'key' => $newData['key'],
                    'data' => $newData['data'],
                ]);

                $progress = $this->output->createProgressBar(5);
            } else {
                $this->info(sprintf('Updating source %s, setting %s...', $newData['source_name'], $newData['key']));

                $setting->key = $newData['key'];
                $setting->data = $newData['data'];
                $setting->save();

                $progress = $this->output->createProgressBar(4);
            }

            $progress->advance();
            $progress->finish();

            $this->info("\n\n");
        }

        return;
    }
}
