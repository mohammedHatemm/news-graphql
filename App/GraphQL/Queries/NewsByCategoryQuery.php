<?php

namespace App\GraphQL\Queries;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NewsByCategoryQuery extends Query
{
  protected $attributes = [
    'name' => 'newsByCategory',
    'description' => 'استعلام للحصول على الأخبار حسب الفئة',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('News'));
  }

  public function args(): array
  {
    return [
      'category_id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الفئة',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    return News::whereHas('categories', function ($query) use ($args) {
      $query->where('categories.id', $args['category_id']);
    })->with(['categories', 'user'])->latest()->get();
  }
}
