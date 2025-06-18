<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class CreateCategoryMutation extends Mutation
{
  protected $attributes = [
    'name' => 'createCategory',
    'description' => 'إنشاء فئة جديدة',
  ];

  public function type(): Type
  {
    return GraphQL::type('Category');
  }

  public function args(): array
  {
    return [
      'name' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'اسم الفئة',
        'rules' => ['max:255', 'required', 'string'],
      ],
      'description' => [
        'type' => Type::string(),
        'description' => 'وصف الفئة',
        'rules' => ['nullable', 'string'],
      ],
      'parent_id' => [
        'type' => Type::id(),
        'description' => 'معرف الفئة الأب',
        'rules' => ['exists:categories,id', 'nullable'],
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    if (!$user ||  !$user->isAdmin()) {

      throw new \Exception('غير مصرح');
    }

    // إنشاء فئة جديدة
    $category = Category::create([
      'name' => $args['name'],
      'description' => $args['description'] ?? null,
      'parent_id' => $args['parent_id'] ?? null,
    ]);

    return $category;
  }
}
