<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class MeQuery extends Query
{
  protected $attributes = [
    'name' => 'me',
    'description' => 'استعلام للحصول على بيانات المستخدم الحالي',
  ];

  public function type(): Type
  {
    return GraphQL::type('User');
  }

  public function resolve($root, $args)
  {
    $user = Auth::guard('sanctum')->user();


    if (!$user) {
      throw new \Exception('غير مصرح');
    }

    return $user;
  }
}
