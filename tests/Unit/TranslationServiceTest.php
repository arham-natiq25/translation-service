<?php

namespace Tests\Unit;

use App\Models\Language;
use App\Models\Tag;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as TestsTestCase;

class TranslationServiceTest extends TestsTestCase
{
    use RefreshDatabase; // This resets the database after each test
    protected $translationService;
    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the service we want to test
        $this->translationService = app(TranslationService::class);

        // Create test data
        Language::create(['code' => 'en', 'name' => 'English']);
        Tag::create(['name' => 'mobile']);
    }
    public function test_it_can_create_a_translation()
    {
        // Act: Call the method we're testing
        $translation = $this->translationService->createTranslation(
            'test.key',
            'en',
            'Test content',
            ['mobile']
        );

        // Assert: Verify the results
        $this->assertDatabaseHas('translation_keys', [
            'key' => 'test.key',
        ]);

        $this->assertDatabaseHas('translations', [
            'translation_key_id' => $translation->translation_key_id,
            'content' => 'Test content',
        ]);
    }
    public function test_it_can_update_a_translation()
    {
        // Arrange: Set up the test data
        $translation = $this->translationService->createTranslation(
            'update.test',
            'en',
            'Original content'
        );

        // Act: Call the method we're testing
        $updatedTranslation = $this->translationService->updateTranslation(
            $translation->id,
            'Updated content'
        );

        // Assert: Verify the results
        $this->assertEquals('Updated content', $updatedTranslation->content);

        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'content' => 'Updated content',
        ]);
    }

}
