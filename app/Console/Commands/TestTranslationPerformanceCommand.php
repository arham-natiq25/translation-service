<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestTranslationPerformanceCommand extends Command
{
    protected $signature = 'translations:test-performance {language=en} {--tags=*}';
    protected $description = 'Test the performance of the translation export endpoint';

    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    public function handle()
    {
        $language = $this->argument('language');
        $tags = $this->option('tags');

        $this->info("Testing performance for language: {$language}");
        if (!empty($tags)) {
            $this->info("With tags: " . implode(', ', $tags));
        }

        // Clear cache to ensure we're testing actual database performance
        $this->info("Clearing cache...");
        $this->call('cache:clear');

        // Test database query performance
        $this->info("Testing database query performance...");
        $startTime = microtime(true);

        DB::enableQueryLog();
        $translations = $this->translationService->getTranslationsForLanguage($language, $tags);
        $queries = DB::getQueryLog();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $this->info("Retrieved " . count($translations) . " translations in {$executionTime}ms");
        $this->info("Number of queries executed: " . count($queries));

        // Test with cache
        $this->info("Testing with cache...");
        $startTime = microtime(true);

        DB::flushQueryLog();
        DB::enableQueryLog();
        $translations = $this->translationService->getTranslationsForLanguage($language, $tags);
        $queries = DB::getQueryLog();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $this->info("Retrieved " . count($translations) . " translations from cache in {$executionTime}ms");
        $this->info("Number of queries executed: " . count($queries));

        return Command::SUCCESS;
    }
}
