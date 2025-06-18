<?php

namespace App\GraphQL\Mutations;

use App\Models\News;
use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class CreateNewsMutation extends Mutation
{
  protected $attributes = [
    'name' => 'createNews',
    'description' => 'إنشاء خبر جديد',
  ];

  public function type(): Type
  {
    return GraphQL::type('News');
  }

  public function args(): array
  {
    return [
      'title' => [ // تغيير من name إلى title
        'type' => Type::nonNull(Type::string()),
        'description' => 'عنوان الخبر',
        'rules' => ['max:255', 'required', 'string'],
      ],
      'content' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'محتوى الخبر',
      ],
      'category_ids' => [
        'type' => Type::nonNull(Type::listOf(Type::id())),
        'description' => 'معرفات الفئات',
        'rules' => ['required', 'min:1'],
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    if (!$user) {
      throw new \Exception('غير مصرح');
    }

    // التحقق من وجود الفئات
    foreach ($args['category_ids'] as $categoryId) {
      $categoryExists = Category::where('id', $categoryId)->exists();
      if (!$categoryExists) {
        throw new \Exception('الفئة غير موجودة: ' . $categoryId);
      }
    }

    // إنشاء خبر جديد
    $news = News::create([
      'title' => $args['title'], // تغيير من name إلى title
      'content' => $args['content'],
      'user_id' => $user->id,
    ]);

    // ربط الخبر بالفئات
    $news->categories()->attach($args['category_ids']);

    return $news->load('categories', 'user');
  }
}
