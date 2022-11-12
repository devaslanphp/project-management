<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateLangFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locale:translate-file {locale}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a lang file based on a lang/*.json file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locale = $this->argument('locale');
        Artisan::call('translatable:export ' . $locale);
        $filePath = lang_path($locale . '.json');
        if (File::exists($filePath)) {
            $this->info('Working, please wait...');
            $results = [];
            $localeFile = File::get($filePath);
            $localeFileContent = array_keys(json_decode($localeFile, true));
            $translator = new GoogleTranslate($locale);
            $translator->setSource('en');
            foreach ($localeFileContent as $key) {
                $results[$key] = $translator->translate($key);
            }
            File::put($filePath, json_encode($results, JSON_UNESCAPED_UNICODE));
            return Command::SUCCESS;
        } else {
            $this->error('Locale file ' . $locale . '.json not exists!');
            return Command::FAILURE;
        }
    }
}
