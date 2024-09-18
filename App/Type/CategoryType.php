<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class CategoryType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Category',
			'fields' => function () {
				return [
					'id_category' => [
						'type' => Types::int(),
						'description' => 'Category ID'
					],
					'category_name' => [
						'type' => Types::string(),
						'description' => 'Category Name'
					],
					'icon_svg' => [
						'type' => Types::string(),
						'description' => 'Icon SVG File'
					],
					'count_item' => [
						'type' => Types::int(),
						'description' => 'Category Counter Item from Product'
					],
					'products' => [
						'type' => Types::listOf(Types::product()),
						'description' => 'Products',
						'resolve' => function ($root) {
							return DB::select("SELECT c.*, p.* FROM product p LEFT JOIN category c ON c.id_category = p.id_category WHERE p.id_category = {$root->id_category}");
						}
					]
					/*'description' => [
						'type' => Types::string(),
						'description' => 'Descrition'
					],
					'handle' => [
						'type' => Types::string(),
						'description' => 'Handle'
					],
					'tag' => [
						'type' => Types::string(),
						'description' => 'Tag'
					],
					'products' => [
						'type' => Types::listOf(Types::product()),
						'description' => 'Products',
						'resolve' => function ($root) {
							return DB::select("SELECT c.*, p.* FROM product p LEFT JOIN category c ON c.id_category = p.id_product_category WHERE p.id_product_category = {$root->id_category}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
