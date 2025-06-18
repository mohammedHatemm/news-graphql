<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LogoutResponseType extends GraphQLType
{
  protected $attributes = [
    'name' => 'LogoutResponse',
    'description' => 'نوع استجابة تسجيل الخروج',
  ];

  public function fields(): array
  {
    return [
      'message' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'رسالة الاستجابة',
      ],
    ];
  }
}
