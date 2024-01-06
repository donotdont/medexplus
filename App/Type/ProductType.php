<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Product',
			'fields' => function(){
				return [
					'id_product' => [
						'type' => Types::int(),
						'description' => 'Product ID'
					],
					'id_category'=>[
						'type' => Types::int(),
						'description' => 'Category ID'
					],
					'product_group' => [
						'type' => Types::string(),
						'description' => 'Product Group'
					],
					'product_cover' => [
						'type' => Types::string(),
						'description' => 'Product Cover'
					],
					'product_name' => [
						'type' => Types::string(),
						'description' => 'Product Name'
					],
					'product_brand' => [
						'type' => Types::string(),
						'description' => 'Product Brand'
					],
					'product_description_th' => [
						'type' => Types::string(),
						'description' => 'Descrition Thai'
					],
					'product_description_en' => [
						'type' => Types::string(),
						'description' => 'Descrition English'
					],
					'product_price' => [
						'type' => Types::float(),
						'description' => 'Product Price'
					],
					'product_quantity' => [
						'type' => Types::int(),
						'description' => 'Product Quantity'
					],
					'product_status' => [
						'type' => Types::int(),
						'description' => 'Product Status'
					],
					'category' => [
						'type' => Types::category(),
						'description' => 'Category',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, c.* FROM category c LEFT JOIN product p ON p.id_category = c.id_category WHERE p.id_product = {$root->id_product}");
						}
					],
					/*'attributes' => [
						'type' => Types::attribute(),
						'description' => 'Attribute',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, a.* FROM attribute a LEFT JOIN product p ON p.id_product = a.id_product WHERE p.id_product = {$root->id_product}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
