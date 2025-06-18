<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\Hash;

class RegisterMutation extends Mutation
{
  protected $attributes = [
    'name' => 'register',
    'description' => 'تسجيل مستخدم جديد',
  ];

  public function type(): Type
  {
    return GraphQL::type('AuthPayload');
  }

  public function args(): array
  {
    return [
      'username' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'اسم المستخدم',
      ],
      'email' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'البريد الإلكتروني',
        'rules' => ['email', 'unique:users'],
      ],
      'password' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'كلمة المرور',
        'rules' => ['min:8'],
      ],
      'password_confirmation' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'تأكيد كلمة المرور',
      ],
      'phone' => [
        'type' => Type::string(),
        'description' => 'رقم الهاتف',
      ],
      'role' => [
        'type' => Type::string(),
        'description' => 'دور المستخدم',
        'rules' => ['in:admin,user'],
      ],
    ];
  }

  public function resolve($root, $args)
  {
    // التحقق من تطابق كلمات المرور
    if ($args['password'] !== $args['password_confirmation']) {
      throw new \Exception('كلمات المرور غير متطابقة');
    }

    // إنشاء مستخدم جديد
    $user = User::create([
      'username' => $args['username'],
      'email' => $args['email'],
      'password' => Hash::make($args['password']),
      'phone' => $args['phone'] ?? null,
      'role' => $args['role'] ?? 'user',
    ]);

    // إنشاء رمز مصادقة
    $token = $user->createToken($args['username'])->plainTextToken;

    return [
      'user' => $user,
      'token' => $token,
    ];
  }
}
