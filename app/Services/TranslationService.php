<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\TranslationKey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationService
{
    /**
     * Create a new translation
     */
    public function createTranslation(string $key, string $languageCode, string $content, array $tags = []): Translation
    {
        // Start a transaction to ensure data integrity
        return DB::transaction(function () use ($key, $languageCode, $content, $tags) {
            // Get or create the translation key
            $translationKey = TranslationKey::firstOrCreate(['key' => $key]);

            // Get the language
            $language = Language::where('code', $languageCode)->firstOrFail();

            // Create the translation
            $translation = Translation::updateOrCreate(
                ['translation_key_id' => $translationKey->id, 'language_id' => $language->id],
                ['content' => $content]
            );

            // Process tags
            if (!empty($tags)) {
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }

                $translationKey->tags()->sync($tagIds);
            }

            // Clear cache for this language
            $this->clearLanguageCache($languageCode);

            return $translation;
        });
    }

    /**
     * Update an existing translation
     */
    public function updateTranslation(int $id, string $content): Translation
    {
        $translation = Translation::findOrFail($id);
        $translation->content = $content;
        $translation->save();

        // Clear cache for this language
        $language = $translation->language;
        $this->clearLanguageCache($language->code);

        return $translation;
    }

    /**
     * Search translations by various criteria
     */
    public function searchTranslations(array $criteria): Collection
    {
        $query = Translation::query()
            ->join('translation_keys', 'translations.translation_key_id', '=', 'translation_keys.id')
            ->join('languages', 'translations.language_id', '=', 'languages.id')
            ->select('translations.*', 'translation_keys.key', 'languages.code as language_code');

        // Filter by key
        if (isset($criteria['key'])) {
            $query->where('translation_keys.key', 'like', "%{$criteria['key']}%");
        }

        // Filter by language
        if (isset($criteria['language'])) {
            $query->where('languages.code', $criteria['language']);
        }

        // Filter by content
        if (isset($criteria['content'])) {
            $query->where('translations.content', 'like', "%{$criteria['content']}%");
        }

        // Filter by tags
        if (isset($criteria['tags']) && is_array($criteria['tags'])) {
            $query->join('translation_tag', 'translation_keys.id', '=', 'translation_tag.translation_key_id')
                  ->join('tags', 'translation_tag.tag_id', '=', 'tags.id')
                  ->whereIn('tags.name', $criteria['tags'])
                  ->groupBy('translations.id')
                  ->havingRaw('COUNT(DISTINCT tags.id) = ?', [count($criteria['tags'])]);
        }

        return $query->get();
    }

    /**
     * Get all translations for a specific language
     */
    public function getTranslationsForLanguage(string $languageCode, array $tags = null): array
    {
        $cacheKey = "translations:{$languageCode}:" . ($tags ? implode(',', $tags) : 'all');

        // Try to get from cache first
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($languageCode, $tags) {
            $query = Translation::query()
                ->join('translation_keys', 'translations.translation_key_id', '=', 'translation_keys.id')
                ->join('languages', 'translations.language_id', '=', 'languages.id')
                ->where('languages.code', $languageCode)
                ->select('translation_keys.key', 'translations.content');

            // Filter by tags if provided
            if ($tags) {
                $query->join('translation_tag', 'translation_keys.id', '=', 'translation_tag.translation_key_id')
                      ->join('tags', 'translation_tag.tag_id', '=', 'tags.id')
                      ->whereIn('tags.name', $tags)
                      ->groupBy('translations.id');
            }

            // Convert to key-value format
            $translations = [];
            foreach ($query->get() as $translation) {
                $translations[$translation->key] = $translation->content;
            }

            return $translations;
        });
    }

    /**
     * Clear cache for a specific language
     */
    private function clearLanguageCache(string $languageCode): void
    {
        // Clear all cache keys related to this language
        Cache::forget("translations:{$languageCode}:all");

        // Also clear any tag-specific caches
        $tags = Tag::all()->pluck('name')->toArray();
        foreach ($tags as $tag) {
            Cache::forget("translations:{$languageCode}:{$tag}");
        }
    }
}
