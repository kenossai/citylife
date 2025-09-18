<?php
// Copyright 2025 Kenneth Ossai
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     https://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'source',
        'is_active',
        'gdpr_consent',
        'gdpr_consent_date',
        'gdpr_consent_ip',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
        'preferences',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'gdpr_consent' => 'boolean',
        'gdpr_consent_date' => 'datetime',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->unsubscribe_token)) {
                $model->unsubscribe_token = Str::random(64);
            }
            if (empty($model->subscribed_at)) {
                $model->subscribed_at = now();
            }
        });
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for GDPR compliant subscribers
     */
    public function scopeGdprCompliant($query)
    {
        return $query->where('gdpr_consent', true);
    }

    /**
     * Subscribe a user to the newsletter
     */
    public static function subscribe(string $email, array $data = []): self
    {
        return self::updateOrCreate(
            ['email' => $email],
            array_merge([
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'gdpr_consent' => $data['gdpr_consent'] ?? false,
                'gdpr_consent_date' => $data['gdpr_consent'] ?? false ? now() : null,
                'gdpr_consent_ip' => $data['gdpr_consent_ip'] ?? null,
            ], $data)
        );
    }

    /**
     * Unsubscribe a user from the newsletter
     */
    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    /**
     * Resubscribe a user to the newsletter
     */
    public function resubscribe(): void
    {
        $this->update([
            'is_active' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Generate unsubscribe URL
     */
    public function getUnsubscribeUrlAttribute(): string
    {
        return route('newsletter.unsubscribe', ['token' => $this->unsubscribe_token]);
    }
}
