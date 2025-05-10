<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\TranslationKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TranslationsApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a token for authentication
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Create test data
        Language::create(['code' => 'en', 'name' => 'English']);
        Language::create(['code' => 'fr', 'name' => 'French']);

        Tag::create(['name' => 'mobile']);
        Tag::create(['name' => 'web']);
    }
    public function test_it_can_create_a_translation()
    {
        // Act: Make a POST request to create a translation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/translations', [
            'key' => 'welcome.message',
            'language_code' => 'en',
            'content' => 'Welcome to our application',
            'tags' => ['mobile', 'web'],
        ]);

        // Assert: Check the response
        $response->assertStatus(201) // 201 = Created
                 ->assertJsonPath('data.key', 'welcome.message')
                 ->assertJsonPath('data.language', 'en')
                 ->assertJsonPath('data.content', 'Welcome to our application');
    }
    public function test_it_can_get_translations()
    {
        // Arrange: Create some translations
        $key = TranslationKey::create(['key' => 'test.key']);
        $language = Language::where('code', 'en')->first();

        Translation::create([
            'translation_key_id' => $key->id,
            'language_id' => $language->id,
            'content' => 'Test content',
        ]);

        // Act: Make a GET request to fetch translations
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/translations');

        // Assert: Check the response
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }
    public function test_it_can_export_translations()
    {
        // Arrange: Create some translations
        $key = TranslationKey::create(['key' => 'export.test']);
        $language = Language::where('code', 'en')->first();

        Translation::create([
            'translation_key_id' => $key->id,
            'language_id' => $language->id,
            'content' => 'Export test content',
        ]);

        // Act: Make a GET request to export translations
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/export?language=en');

        // Assert: Check the response
        $response->assertStatus(200)
                 ->assertJson([
                     'export.test' => 'Export test content',
                 ]);
    }



}
