<?php

namespace App\GraphQL\Queries;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AllNewsQuery extends Query
{
  protected $attributes = [
    'name' => 'allNews',
    'description' => 'استعلام للحصول على قائمة الأخبار',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('News'));
  }

  public function resolve($root, $args)
  {
    return News::with(['categories', 'user'])->latest()->get();
  }
}
