<?php

namespace App\GraphQL\Queries;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NewsByDateQuery extends Query
{
  protected $attributes = [
    'name' => 'newsByDate',
    'description' => 'استعلام للحصول على الأخبار حسب التاريخ',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('News'));
  }

  public function args(): array
  {
    return [
      'date' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'التاريخ (Y-m-d)',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    return News::whereDate('created_at', $args['date'])
      ->with(['categories', 'user'])
      ->latest()
      ->get();
  }
}
