<?php

namespace App\GraphQL\Types;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NewsType extends GraphQLType
{
  protected $attributes = [
    'name' => 'News',
    'description' => 'نوع الأخبار',
    'model' => News::class,
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الخبر',
      ],
      'title' => [ // تغيير من name إلى title
        'type' => Type::nonNull(Type::string()),
        'description' => 'عنوان الخبر',
      ],
      'content' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'محتوى الخبر',
      ],
      'user_id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف المستخدم الذي أنشأ الخبر',
      ],
      'user' => [
        'type' => GraphQL::type('User'),
        'description' => 'المستخدم الذي أنشأ الخبر',
        'resolve' => function ($root) {
          return $root->user;
        },
      ],
      'categories' => [
        'type' => Type::listOf(GraphQL::type('Category')),
        'description' => 'الفئات المرتبطة بالخبر',
        'resolve' => function ($root) {
          return $root->categories;
        },
      ],
      'created_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ إنشاء الخبر',
        'resolve' => function ($root) {
          return $root->created_at->toDateTimeString();
        },
      ],
      'updated_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ آخر تحديث للخبر',
        'resolve' => function ($root) {
          return $root->updated_at->toDateTimeString();
        },
      ],
    ];
  }
}
