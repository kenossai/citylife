<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSEO;

class SEOObserver
{
    /**
     * Handle the model "creating" event.
     */
    public function creating(Model $model): void
    {
        if (in_array(HasSEO::class, class_uses_recursive($model))) {
            $model->autoPopulateSEO();
        }
    }

    /**
     * Handle the model "updating" event.
     */
    public function updating(Model $model): void
    {
        if (in_array(HasSEO::class, class_uses_recursive($model))) {
            // Only auto-populate if SEO fields are still empty
            if (empty($model->meta_title) || empty($model->meta_description)) {
                $model->autoPopulateSEO();
            }
        }
    }
}
