<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Display a listing of the translations.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $criteria = $request->only(['key', 'language', 'content', 'tags']);
        $translations = $this->translationService->searchTranslations($criteria);

        return TranslationResource::collection($translations);
    }

    /**
     * Store a newly created translation in storage.
     */
    public function store(StoreTranslationRequest $request): TranslationResource
    {
        $translation = $this->translationService->createTranslation(
            $request->key,
            $request->language_code,
            $request->content,
            $request->tags ?? []
        );

        return new TranslationResource($translation);
    }

    /**
     * Display the specified translation.
     */
    public function show(Translation $translation): TranslationResource
    {
        return new TranslationResource($translation);
    }

    /**
     * Update the specified translation in storage.
     */
    public function update(UpdateTranslationRequest $request, Translation $translation): TranslationResource
    {
        $translation = $this->translationService->updateTranslation(
            $translation->id,
            $request->content
        );

        return new TranslationResource($translation);
    }

    /**
     * Remove the specified translation from storage.
     */
    public function destroy(Translation $translation): JsonResponse
    {
        $translation->delete();

        return response()->json(['message' => 'Translation deleted successfully']);
    }

    /**
     * Export translations for a specific language.
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'language' => 'required|string|exists:languages,code',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|exists:tags,name',
        ]);

        $translations = $this->translationService->getTranslationsForLanguage(
            $request->language,
            $request->tags
        );

        return response()->json($translations);
    }
}
