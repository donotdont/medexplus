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
					'id_product_category'=>[
						'type' => Types::int(),
						'description' => 'Category ID'
					],
					'name' => [
						'type' => Types::string(),
						'description' => 'Name'
					],
					'model' => [
						'type' => Types::string(),
						'description' => 'Model'
					],
					'description' => [
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
					'active' => [
						'type' => Types::int(),
						'description' => 'Active'
					],
					'category' => [
						'type' => Types::category(),
						'description' => 'Category',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, c.* FROM category c LEFT JOIN product p ON p.id_product_category = c.id_category WHERE p.id_product = {$root->id_product}");
						}
					],
					'attributes' => [
						'type' => Types::attribute(),
						'description' => 'Attribute',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, a.* FROM attribute a LEFT JOIN product p ON p.id_product = a.id_product WHERE p.id_product = {$root->id_product}");
						}
					]
				];
			}
		];
		parent::__construct($config);
	}
}
