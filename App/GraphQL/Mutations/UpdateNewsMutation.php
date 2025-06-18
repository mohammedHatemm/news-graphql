<?php

namespace App\GraphQL\Mutations;

use App\Models\News;
use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class UpdateNewsMutation extends Mutation
{
  protected $attributes = [
    'name' => 'updateNews',
    'description' => 'تحديث خبر موجود',
  ];

  public function type(): Type
  {
    return GraphQL::type('News');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الخبر',
      ],
      'title' => [ // تغيير من name إلى title
        'type' => Type::string(),
        'description' => 'عنوان الخبر',
        'rules' => ['max:255'],
      ],
      'content' => [
        'type' => Type::string(),
        'description' => 'محتوى الخبر',
      ],
      'category_ids' => [
        'type' => Type::listOf(Type::id()),
        'description' => 'معرفات الفئات',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    // البحث عن الخبر
    $news = News::findOrFail($args['id']);

    // التحقق من أن المستخدم هو صاحب الخبر
    if (!$user || $news->user_id !== $user->id) {
      throw new \Exception('غير مصرح');
    }

    // تحديث الخبر
    $updateData = [];
    if (isset($args['title'])) { // تغيير من name إلى title
      $updateData['title'] = $args['title'];
    }
    if (isset($args['content'])) {
      $updateData['content'] = $args['content'];
    }

    $news->update($updateData);

    // تحديث الفئات إذا تم تقديمها
    if (isset($args['category_ids'])) {
      // التحقق من وجود الفئات
      foreach ($args['category_ids'] as $categoryId) {
        $categoryExists = Category::where('id', $categoryId)->exists();
        if (!$categoryExists) {
          throw new \Exception('الفئة غير موجودة: ' . $categoryId);
        }
      }

      $news->categories()->sync($args['category_ids']);
    }

    return $news->load('categories', 'user');
  }
}
