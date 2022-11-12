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
    protected $signature = 'make:lang {locales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a JSON translation file and do the translations for you.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locales = explode(',', $this->argument('locales'));
        foreach ($locales as $locale) {
            Artisan::call('translatable:export ' . $locale);
            $filePath = lang_path($locale . '.json');
            if (File::exists($filePath)) {
                $this->info('Translating ' . $locale . ', please wait...');
                $results = [];
                $localeFile = File::get($filePath);
                $localeFileContent = array_keys(json_decode($localeFile, true));
                $translator = new GoogleTranslate($locale);
                $translator->setSource('en');
                foreach ($localeFileContent as $key) {
                    $results[$key] = $translator->translate($key);
                }
                File::put($filePath, json_encode($results, JSON_UNESCAPED_UNICODE));
            }
        }
        return Command::SUCCESS;
    }
}
