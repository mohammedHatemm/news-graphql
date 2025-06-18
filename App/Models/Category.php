<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


use App\Models\News;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'parent_id'];

    /**
     * الحصول على الفئة الأب
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * الحصول على الفئات الفرعية
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * الحصول على الأخبار المرتبطة بهذه الفئة
     */
    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_categories');
    }
}
