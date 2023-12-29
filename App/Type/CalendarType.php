<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class CalendarType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Calendar',
			'fields' => function(){
				return [
					'id_calendar' => [
						'type' => Types::int(),
						'description' => 'Calendar ID'
					],
					'id_user'=>[
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'start_datetime' => [
						'type' => Types::string(),
						'description' => 'Start Datetime'
					],
					'end_datetime' => [
						'type' => Types::string(),
						'description' => 'End Datetime'
					],
					'title_th' => [
						'type' => Types::string(),
						'description' => 'Title Thai'
					],
					'title_en' => [
						'type' => Types::string(),
						'description' => 'Title English'
					],
					'description_th' => [
						'type' => Types::string(),
						'description' => 'Description Thai'
					],
					'description_en' => [
						'type' => Types::string(),
						'description' => 'Description English'
					],
					'link' => [
						'type' => Types::string(),
						'description' => 'Link'
					],
					'created_at' => [
						'type' => Types::string(),
						'description' => 'Created at'
					],
					'updated_at' => [
						'type' => Types::string(),
						'description' => 'Updated at'
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
