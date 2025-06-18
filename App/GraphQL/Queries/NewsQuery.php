<?php

namespace App\GraphQL\Queries;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NewsQuery extends Query
{
  protected $attributes = [
    'name' => 'news',
    'description' => 'استعلام للحصول على خبر واحد',
  ];

  public function type(): Type
  {
    return GraphQL::type('News');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الخبر',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    return News::with(['categories', 'user'])->find($args['id']);
  }
}
