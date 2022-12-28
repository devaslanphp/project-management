<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trans:missing {base}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a JSON translation for missing keys, based on a locale file "base".';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $base = $this->argument('base');
        $locales = config('system.locales.list');
        $baseTranslations = json_decode(File::get(lang_path($base . '.json')), true);
        $this->info('Found ' . sizeof($locales) . ' locales. Performing, please wait...');
        $bar = $this->getOutput()->createProgressBar(sizeof($locales));
        $bar->start();
        foreach ($locales as $locale => $name) {
            if ($locale !== $base && $locale !== config('app.fallback_locale')) {
                $filePath = lang_path($locale . '.json');
                if (File::exists($filePath)) {
                    $localeTranslations = json_decode(File::get(lang_path($locale . '.json')), true);
                    $translator = new GoogleTranslate($locale);
                    $translator->setSource('en');
                    $newLocaleTranslations = [];
                    foreach ($baseTranslations as $kbt => $baseTranslation) {
                        if (!array_key_exists($kbt, $localeTranslations)) {
                            $newLocaleTranslations[$kbt] = $translator->translate($kbt);
                        } else {
                            $newLocaleTranslations[$kbt] = $localeTranslations[$kbt];
                        }
                    }
                    File::put($filePath, json_encode($newLocaleTranslations, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
                }
            }
            $bar->advance();
        }
        $bar->finish();
        return Command::SUCCESS;
    }
}
