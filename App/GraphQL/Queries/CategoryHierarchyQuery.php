<?php

namespace App\GraphQL\Queries;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CategoryHierarchyQuery extends Query
{
  protected $attributes = [
    'name' => 'categoryHierarchy',
    'description' => 'استعلام للحصول على التسلسل الهرمي للفئات',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('Category'));
  }

  public function resolve($root, $args)
  {
    return Category::getCategoryHierarchy();
  }
}
