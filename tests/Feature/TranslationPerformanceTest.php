<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\TranslationKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TranslationPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Create test data
        Language::create(['code' => 'en', 'name' => 'English']);
        Tag::create(['name' => 'mobile']);

        // Generate test translations (a smaller number for testing)
        $this->generateTestData(100);
    }
    private function generateTestData($count)
    {
        $language = Language::where('code', 'en')->first();

        for ($i = 0; $i < $count; $i++) {
            $key = TranslationKey::create(['key' => "perf.test.{$i}"]);

            Translation::create([
                'translation_key_id' => $key->id,
                'language_id' => $language->id,
                'content' => "Performance test content {$i}",
            ]);
        }
    }
    public function test_export_endpoint_responds_within_time_limit()
    {
        // Clear cache to ensure we're testing actual database performance
        Artisan::call('cache:clear');

        // Measure response time
        $startTime = microtime(true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/export?language=en');

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        // Assert response is successful
        $response->assertStatus(200);

        // Assert response time is under 500ms
        $this->assertLessThan(500, $executionTime,
            "Export endpoint took {$executionTime}ms which exceeds the 500ms limit");

        // Output the execution time for information
        echo "Export endpoint response time: {$executionTime}ms\n";
    }

}
