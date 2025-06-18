<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class DeleteCategoryMutation extends Mutation
{
  protected $attributes = [
    'name' => 'deleteCategory',
    'description' => 'حذف فئة',
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
        'description' => 'معرف الفئة',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    // البحث عن الفئة
    $category = Category::findOrFail($args['id']);

    if (!$user || !$user->can('delete', $category)) {
      throw new \Exception('غير مصرح');
    }

    // حذف الفئة
    $category->delete();

    return [
      'message' => 'تم حذف الفئة بنجاح',
      'success' => true,
    ];
  }
}
