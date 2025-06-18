<?php

namespace App\GraphQL\Mutations;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class DeleteNewsMutation extends Mutation
{
  protected $attributes = [
    'name' => 'deleteNews',
    'description' => 'حذف خبر',
  ];

  public function type(): Type
  {
    return GraphQL::type('DeleteResponse');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الخبر',
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

    // حذف الخبر
    $news->delete();

    return [
      'message' => 'تم حذف الخبر بنجاح',
      'success' => true,
    ];
  }
}
