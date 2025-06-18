<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DeleteResponseType extends GraphQLType
{
  protected $attributes = [
    'name' => 'DeleteResponse',
    'description' => 'نوع استجابة الحذف',
  ];

  public function fields(): array
  {
    return [
      'message' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'رسالة الاستجابة',
      ],
      'success' => [
        'type' => Type::nonNull(Type::boolean()),
        'description' => 'حالة نجاح العملية',
      ],
    ];
  }
}
