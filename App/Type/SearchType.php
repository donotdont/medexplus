<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class SearchType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Search',
			'fields' => function(){
				return [
					'id_search' => [
						'type' => Types::int(),
						'description' => 'Search ID'
					],
					'id_user'=>[
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'model' => [
						'type' => Types::string(),
						'description' => 'Model'
					],
					'title_th' => [
						'type' => Types::string(),
						'description' => 'Title Thai'
					],
					'title_en' => [
						'type' => Types::string(),
						'description' => 'Title English'
					],
					'content_th' => [
						'type' => Types::string(),
						'description' => 'Content Thai'
					],
					'content_en' => [
						'type' => Types::string(),
						'description' => 'Content English'
					],
					'image_cover' => [
						'type' => Types::string(),
						'description' => 'Image Cover'
					],
					'description_th' => [
						'type' => Types::string(),
						'description' => 'Description Thai'
					],
					'description_en' => [
						'type' => Types::string(),
						'description' => 'Description English'
					],
					'active' => [
						'type' => Types::int(),
						'description' => 'Active'
					],
					'created_at' => [
						'type' => Types::string(),
						'description' => 'Created at'
					],
					'updated_at' => [
						'type' => Types::string(),
						'description' => 'Updated at'
					],
					'i18n' => [
						'type' => Types::string(),
						'description' => 'Current Language'
					],
					
					/*'category' => [
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
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
