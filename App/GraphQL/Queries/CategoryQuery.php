<?php

namespace App\GraphQL\Queries;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CategoryQuery extends Query
{
  protected $attributes = [
    'name' => 'category',
    'description' => 'استعلام للحصول على فئة واحدة',
  ];

  public function type(): Type
  {
    return GraphQL::type('Category');
  }

  public function args(): array
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::id()),
        'description' => 'معرف الفئة',
      ],
    ];
  }

  public function resolve($root, $args)
  {
    return Category::with(['children', 'parent', 'news'])->findOrFail($args['id']);
  }
}
