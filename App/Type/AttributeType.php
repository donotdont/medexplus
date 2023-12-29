<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class AttributeType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Attribute',
			'fields' => function(){
				return [
					'id_attribute' => [
						'type' => Types::int(),
						'description' => 'Attribute ID'
					],
					'id_product'=>[
						'type' => Types::int(),
						'description' => 'Product ID'
					],
					'active' => [
						'type' => Types::int(),
						'description' => 'Active'
					],
					'product_code' => [
						'type' => Types::string(),
						'description' => 'Product Code'
					],
					'size' => [
						'type' => Types::string(),
						'description' => 'Size'
					],
					'color' => [
						'type' => Types::string(),
						'description' => 'Color'
					],
					'price' => [
						'type' => Types::float(),
						'description' => 'Price'
					],
					'cost_price' => [
						'type' => Types::float(),
						'description' => 'Cost Price'
					],
					'sale_price' => [
						'type' => Types::float(),
						'description' => 'Sale Price'
					],
					'price_tag' => [
						'type' => Types::float(),
						'description' => 'Price Tag'
					],
					'quantity' => [
						'type' => Types::int(),
						'description' => 'Quantity'
					],
					'handle' => [
						'type' => Types::string(),
						'description' => 'Handle'
					],
					'tag' => [
						'type' => Types::string(),
						'description' => 'Tag'
					],
					'category' => [
						'type' => Types::category(),
						'description' => 'Category',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, c.* FROM category c LEFT JOIN product p ON p.id_product_category = c.id_category WHERE p.id_product = {$root->id_product}");
						}
					],
					'product' => [
						'type' => Types::product(),
						'description' => 'Product',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, c.* FROM category c LEFT JOIN product p ON p.id_product_category = c.id_category WHERE p.id_product = {$root->id_product}");
						}
					]
				];
			}
		];
		parent::__construct($config);
	}
}
