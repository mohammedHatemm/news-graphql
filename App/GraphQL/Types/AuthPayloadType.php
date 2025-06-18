<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AuthPayloadType extends GraphQLType
{
  protected $attributes = [
    'name' => 'AuthPayload',
    'description' => 'نوع استجابة المصادقة',
  ];

  public function fields(): array
  {
    return [
      'token' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'رمز المصادقة',
      ],
      'user' => [
        'type' => GraphQL::type('User'),
        'description' => 'بيانات المستخدم',
      ],
    ];
  }
}
