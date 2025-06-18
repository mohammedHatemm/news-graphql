<?php

namespace App\GraphQL\Queries;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CategoriesQuery extends Query
{
  protected $attributes = [
    'name' => 'categories',
    'description' => 'استعلام للحصول على قائمة الفئات',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('Category'));
  }

  public function resolve($root, $args)
  {
    return Category::all();
  }
}
