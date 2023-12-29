<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class InstructorType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Instructor',
			'fields' => function(){
				return [
					'id_instructor' => [
						'type' => Types::int(),
						'description' => 'Instructor ID'
					],
					'user_id' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'code' => [
						'type' => Types::string(),
						'description' => 'Code'
					],
					'name_th' => [
						'type' => Types::string(),
						'description' => 'Name Thai'
					],
					'name_en' => [
						'type' => Types::string(),
						'description' => 'Name English'
					],
					'image_url' => [
						'type' => Types::string(),
						'description' => 'Image URL'
					],
					'major' => [
						'type' => Types::string(),
						'description' => 'Major'
					],
					'university' => [
						'type' => Types::string(),
						'description' => 'University'
					],
					'position_name_th' => [
						'type' => Types::string(),
						'description' => 'Position Name Thai'
					],
					'position_name_en' => [
						'type' => Types::string(),
						'description' => 'Position Name English'
					],
					'key_id' => [
						'type' => Types::string(),
						'description' => 'Key ID'
					],
					'parent_key_id' => [
						'type' => Types::string(),
						'description' => 'Parent Key ID'
					],
					'size' => [
						'type' => Types::string(),
						'description' => 'Size'
					],
					'education_th' => [
						'type' => Types::string(),
						'description' => 'Education Thai'
					],
					'education_en' => [
						'type' => Types::string(),
						'description' => 'Education English'
					],
					'portfolio_th' => [
						'type' => Types::string(),
						'description' => 'Portfolio Thai'
					],
					'portfolio_en' => [
						'type' => Types::string(),
						'description' => 'Portfolio English'
					],
					'room_th' => [
						'type' => Types::string(),
						'description' => 'Room Thai'
					],
					'room_en' => [
						'type' => Types::string(),
						'description' => 'Room English'
					],
					'email' => [
						'type' => Types::string(),
						'description' => 'Email'
					],
					'tel_th' => [
						'type' => Types::string(),
						'description' => 'Tel Thai'
					],
					'tel_en' => [
						'type' => Types::string(),
						'description' => 'Tel English'
					],
					'phone' => [
						'type' => Types::string(),
						'description' => 'Phone'
					],
					'website' => [
						'type' => Types::string(),
						'description' => 'Website'
					],
					'order_index' => [
						'type' => Types::int(),
						'description' => 'Order Index'
					],
					'created_at' => [
						'type' => Types::string(),
						'description' => 'Created at'
					],
					'updated_at' => [
						'type' => Types::string(),
						'description' => 'Updated at'
					],
					/*'users' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'Address',
						'resolve' => function($root){
							return DB::select("SELECT u.*, a.* FROM users u LEFT JOIN address a ON a.user_id = u.user_id WHERE u.user_id = {$root->id}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
