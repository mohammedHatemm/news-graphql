<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Category;
use App\Models\User;


class News extends Model
{
    protected $fillable = ['title', 'content', 'user_id'];

    /**
     * الحصول على المستخدم الذي أنشأ الخبر
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على الفئات المرتبطة بهذا الخبر
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'news_categories');
    }
}
