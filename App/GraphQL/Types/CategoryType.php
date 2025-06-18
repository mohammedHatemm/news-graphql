<?php

namespace App\GraphQL\Types;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CategoryType extends GraphQLType
{
  protected $attributes = [
    'name' => 'Category',
    'description' => 'نوع الفئة',
    'model' => Category::class,
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الفئة',
      ],
      'name' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'اسم الفئة',
      ],
      'description' => [
        'type' => Type::string(),
        'description' => 'وصف الفئة',
      ],
      'parent_id' => [
        'type' => Type::id(),
        'description' => 'معرف الفئة الأب',
      ],
      'parent' => [
        'type' => GraphQL::type('Category'),
        'description' => 'الفئة الأب',
        'resolve' => function ($root) {
          return $root->parent;
        },
      ],
      'children' => [
        'type' => Type::listOf(GraphQL::type('Category')),
        'description' => 'الفئات الفرعية',
        'resolve' => function ($root) {
          return $root->children;
        },
      ],
      'news' => [
        'type' => Type::listOf(GraphQL::type('News')),
        'description' => 'الأخبار المرتبطة بالفئة',
        'resolve' => function ($root) {
          return $root->news;
        },
      ],
      'ancestors' => [
        'type' => Type::listOf(GraphQL::type('Category')),
        'description' => 'الفئات الأب التسلسلية',
        'resolve' => function ($root) {
          return $root->getAncestors();
        },
      ],
      'fullPath' => [
        'type' => Type::string(),
        'description' => 'المسار الكامل للفئة',
        'resolve' => function ($root) {
          return $root->getFullPath();
        },
      ],
      'created_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ إنشاء الفئة',
        'resolve' => function ($root) {
          return $root->created_at->toDateTimeString();
        },
      ],
      'updated_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ آخر تحديث للفئة',
        'resolve' => function ($root) {
          return $root->updated_at->toDateTimeString();
        },
      ],
    ];
  }
}
