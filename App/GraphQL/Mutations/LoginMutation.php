<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Hash;

class LoginMutation extends Mutation
{
  protected $attributes = [
    'name' => 'login',
    'description' => 'تسجيل الدخول',
  ];

  public function type(): Type
  {
    return GraphQL::type('AuthPayload');
  }

  public function args(): array
  {
    return [
      'email' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'البريد الإلكتروني',
        'rules' => ['email'],
      ],
      'password' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'كلمة المرور',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // البحث عن المستخدم
    $user = User::where('email', $args['email'])->first();

    // التحقق من وجود المستخدم وصحة كلمة المرور
    if (!$user || !Hash::check($args['password'], $user->password)) {
      throw new \Exception('بيانات الاعتماد غير صالحة');
    }

    // إنشاء رمز مصادقة
    $token = $user->createToken($user->username)->plainTextToken;

    return [
      'user' => $user,
      'token' => $token,
    ];
  }
}
