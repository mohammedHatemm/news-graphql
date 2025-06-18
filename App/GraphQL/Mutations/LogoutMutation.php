<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Auth;

class LogoutMutation extends Mutation
{
  protected $attributes = [
    'name' => 'logout',
    'description' => 'تسجيل الخروج',
  ];

  public function type(): Type
  {
    return GraphQL::type('LogoutResponse');
  }

  public function resolve($root, $args)
  {
    // التحقق من وجود مستخدم مسجل الدخول
    $user = Auth::guard('sanctum')->user();

    if (!$user) {
      throw new \Exception('غير مصرح');
    }

    // إلغاء رمز المصادقة الحالي
    $user->currentAccessToken()->delete();

    return [
      'message' => 'تم تسجيل الخروج بنجاح',
    ];
  }
}
