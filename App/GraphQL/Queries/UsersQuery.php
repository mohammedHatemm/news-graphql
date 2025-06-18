<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class UsersQuery extends Query
{
  protected $attributes = [
    'name' => 'users',
    'description' => 'استعلام للحصول على قائمة المستخدمين',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('User'));
  }

  public function resolve($root, $args)
  {
    $user = Auth::guard('sanctum')->user();

    if (!$user || !$user->isAdmin()) {
      throw new \Exception('غير مصرح لك بعرض المستخدمين');
    }

    return User::all();
  }
}
