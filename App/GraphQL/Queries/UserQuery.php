<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UserQuery extends Query
{
  protected $attributes = [
    'name' => 'user',
    'description' => 'استعلام للحصول على مستخدم واحد',
  ];

  public function type(): Type
  {
    return GraphQL::type('User');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف المستخدم',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    return User::findOrFail($args['id']);
  }
}
