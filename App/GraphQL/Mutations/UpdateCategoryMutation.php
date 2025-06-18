<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class UpdateCategoryMutation extends Mutation
{
  protected $attributes = [
    'name' => 'updateCategory',
    'description' => 'تحديث فئة موجودة',
  ];

  public function type(): Type
  {
    return GraphQL::type('Category');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الفئة',
      ],
      'name' => [
        'type' => Type::string(),
        'description' => 'اسم الفئة',
        'rules' => ['max:255'],
      ],
      'description' => [
        'type' => Type::string(),
        'description' => 'وصف الفئة',
      ],
      'parent_id' => [
        'type' => Type::id(),
        'description' => 'معرف الفئة الأب',
        'rules' => ['exists:categories,id'],
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    // البحث عن الفئة
    $category = Category::findOrFail($args['id']);

    if (!$user || !$user->can('update', $category)) {
      throw new \Exception('غير مصرح');
    }

    // التحقق من صلاحية parent_id إذا تم تقديمه
    if (isset($args['parent_id'])) {
      // التحقق من أن الفئة لا تكون أب لنفسها
      if ($args['parent_id'] == $category->id) {
        throw new \Exception('لا يمكن أن تكون الفئة أب لنفسها');
      }

      // التحقق من أن الفئة لا تكون أب لأحد أبنائها
      $descendants = $this->getDescendants($category);
      if (in_array($args['parent_id'], $descendants->pluck('id')->toArray())) {
        throw new \Exception('لا يمكن أن تكون الفئة أب لأحد أبنائها');
      }
    }

    // تحديث الفئة
    $updateData = [];
    if (isset($args['name'])) {
      $updateData['name'] = $args['name'];
    }
    if (isset($args['description'])) {
      $updateData['description'] = $args['description'];
    }
    if (isset($args['parent_id'])) {
      $updateData['parent_id'] = $args['parent_id'];
    }

    $category->update($updateData);

    return $category;
  }

  private function getDescendants($category)
  {
    $descendants = collect();

    foreach ($category->children as $child) {
      $descendants->push($child);
      $descendants = $descendants->merge($this->getDescendants($child));
    }

    return $descendants;
  }
}
