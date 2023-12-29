<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class AddressType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Address',
			'fields' => function(){
				return [
					'id' => [
						'type' => Types::int(),
						'description' => 'Address ID'
					],
					'user_id' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'name' => [
						'type' => Types::string(),
						'description' => 'Name'
					],
					'description' => [
						'type' => Types::string(),
						'description' => 'Description'
					],
					'users' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'Address',
						'resolve' => function($root){
							return DB::select("SELECT u.*, a.* FROM users u LEFT JOIN address a ON a.user_id = u.user_id WHERE u.user_id = {$root->id}");
						}
					]
				];
			}
		];
		parent::__construct($config);
	}
}
