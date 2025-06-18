<?php

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UserType extends GraphQLType
{
  protected $attributes = [
    'name' => 'User',
    'description' => 'نوع المستخدم',
    'model' => User::class,
  ];

  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف المستخدم',
      ],
      'username' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'اسم المستخدم',
      ],
      'email' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'البريد الإلكتروني للمستخدم',
      ],
      'phone' => [
        'type' => Type::string(),
        'description' => 'رقم هاتف المستخدم',
      ],
      'role' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'دور المستخدم',
      ],
      'news' => [
        'type' => Type::listOf(GraphQL::type('News')),
        'description' => 'الأخبار التي أنشأها المستخدم',
        'resolve' => function ($root) {
          return $root->news;
        },
      ],
      'created_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ إنشاء المستخدم',
        'resolve' => function ($root) {
          return $root->created_at->toDateTimeString();
        },
      ],
      'updated_at' => [
        'type' => Type::string(),
        'description' => 'تاريخ آخر تحديث للمستخدم',
        'resolve' => function ($root) {
          return $root->updated_at->toDateTimeString();
        },
      ],
    ];
  }
}
