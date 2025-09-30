<?php

namespace App\Traits;

trait HasSEO
{
    /**
     * Get the effective meta title
     */
    public function getEffectiveMetaTitleAttribute(): string
    {
        return $this->meta_title ?? $this->title ?? '';
    }

    /**
     * Get the effective meta description
     */
    public function getEffectiveMetaDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        // Try to get description from common fields
        $fields = ['description', 'excerpt', 'summary', 'content'];
        foreach ($fields as $field) {
            if (isset($this->$field) && $this->$field) {
                return \Illuminate\Support\Str::limit(strip_tags($this->$field), 160);
            }
        }

        return '';
    }

    /**
     * Get the effective meta keywords
     */
    public function getEffectiveMetaKeywordsAttribute(): string
    {
        return $this->meta_keywords ?? '';
    }

    /**
     * Get the effective canonical URL
     */
    public function getEffectiveCanonicalUrlAttribute(): string
    {
        if ($this->canonical_url) {
            return $this->canonical_url;
        }

        // Try to generate canonical URL based on model type
        if (method_exists($this, 'getRouteKeyName') && $this->getRouteKey()) {
            $modelName = strtolower(class_basename($this));
            return route("{$modelName}s.show", $this->getRouteKey());
        }

        return '';
    }

    /**
     * Get the effective OG image
     */
    public function getEffectiveOgImageAttribute(): ?string
    {
        if ($this->og_image) {
            return asset('storage/' . $this->og_image);
        }

        // Try to get image from common fields
        $imageFields = ['featured_image', 'image', 'hero_image', 'thumbnail'];
        foreach ($imageFields as $field) {
            if (isset($this->$field) && $this->$field) {
                return asset('storage/' . $this->$field);
            }
        }

        return null;
    }

    /**
     * Generate automatic SEO fields based on content
     */
    public function generateAutoSEO(): array
    {
        $title = $this->title ?? '';
        $description = $this->getEffectiveMetaDescriptionAttribute();

        // Generate keywords from title and content
        $keywords = $this->generateKeywords();

        return [
            'meta_title' => $title ? $title . ' - City Life International Church' : null,
            'meta_description' => $description,
            'meta_keywords' => implode(', ', $keywords),
        ];
    }

    /**
     * Generate keywords from content
     */
    protected function generateKeywords(): array
    {
        $keywords = ['city life church', 'sheffield church'];

        // Extract keywords from title
        if ($this->title) {
            $titleWords = str_word_count(strtolower($this->title), 1);
            $keywords = array_merge($keywords, array_slice($titleWords, 0, 3));
        }

        // Add model-specific keywords
        $modelName = strtolower(class_basename($this));
        switch ($modelName) {
            case 'event':
                $keywords[] = 'church events';
                if (isset($this->guest_speaker) && $this->guest_speaker) {
                    $keywords[] = $this->guest_speaker;
                }
                break;
            case 'news':
                $keywords[] = 'church news';
                if (isset($this->author) && $this->author) {
                    $keywords[] = $this->author;
                }
                break;
            case 'teachingseries':
                $keywords = array_merge($keywords, ['sermon', 'bible study', 'teaching']);
                if (isset($this->pastor) && $this->pastor) {
                    $keywords[] = $this->pastor;
                }
                break;
        }

        return array_unique(array_filter($keywords));
    }

    /**
     * Auto-populate SEO fields if they're empty
     */
    public function autoPopulateSEO(): void
    {
        $autoSEO = $this->generateAutoSEO();

        foreach ($autoSEO as $field => $value) {
            if (empty($this->$field) && $value) {
                $this->$field = $value;
            }
        }
    }
}
