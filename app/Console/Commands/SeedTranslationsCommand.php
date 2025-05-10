<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\TranslationKey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTranslationsCommand extends Command
{
    protected $signature = 'translations:seed {count=100000}';
    protected $description = 'Seed the database with a large number of translations for performance testing';

    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("Seeding {$count} translations...");

        // Ensure we have languages
        $languages = $this->ensureLanguages();

        // Ensure we have tags
        $tags = $this->ensureTags();

        // Create translation keys and translations in chunks
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $chunkSize = 1000;
        $chunks = ceil($count / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            DB::transaction(function () use ($i, $chunkSize, $count, $languages, $tags, $bar) {
                $currentChunkSize = min($chunkSize, $count - ($i * $chunkSize));

                for ($j = 0; $j < $currentChunkSize; $j++) {
                    $keyIndex = ($i * $chunkSize) + $j;

                    // Create translation key
                    $key = "translation.key.{$keyIndex}";
                    $translationKey = TranslationKey::create(['key' => $key]);

                    // Assign random tags (1-3 tags)
                    $tagCount = rand(1, 3);
                    $randomTags = $tags->random($tagCount);
                    $translationKey->tags()->attach($randomTags->pluck('id')->toArray());

                    // Create translations for each language
                    foreach ($languages as $language) {
                        Translation::create([
                            'translation_key_id' => $translationKey->id,
                            'language_id' => $language->id,
                            'content' => "This is the {$language->code} translation for key {$key}",
                        ]);
                    }

                    $bar->advance();
                }
            });
        }

        $bar->finish();
        $this->newLine();
        $this->info('Seeding completed successfully!');

        return Command::SUCCESS;
    }

    private function ensureLanguages()
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'it', 'name' => 'Italian'],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(['code' => $language['code']], $language);
        }

        return Language::all();
    }

    private function ensureTags()
    {
        $tags = ['mobile', 'desktop', 'web', 'admin', 'user', 'error', 'success', 'notification'];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }

        return Tag::all();
    }
}
