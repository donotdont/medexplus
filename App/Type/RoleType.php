<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class RoleType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Role',
			'fields' => function(){
				return [
					'id_role' => [
						'type' => Types::int(),
						'description' => 'Role ID'
					],
					'name_th' => [
						'type' => Types::string(),
						'description' => 'Name Thai'
					],
					'name_en' => [
						'type' => Types::string(),
						'description' => 'Name English'
					],
					'users' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'Role -> User',
						'resolve' => function($root){
							return DB::select("SELECT u.*, r.* FROM user u LEFT JOIN role r ON r.id_role = u.id_role WHERE u.id_role = {$root->id_role}");
						}
					],
					'permissions' => [
						'type' => Types::listOf(Types::permission()),
						'description' => 'Permission',
						'resolve' => function($root){
							return DB::select("SELECT p.*, r.* FROM permission p LEFT JOIN role r ON r.id_role = p.id_role WHERE p.id_role = {$root->id_role}");
						}
					]
					/*'roles' => [
						'type' => Types::listOf(Types::role()),
						'description' => 'Role',
						'resolve' => function($root){
							return DB::select("SELECT r.*, a.* FROM role r LEFT JOIN users u ON u.id_role = r.id_role WHERE a.id_role = {$root->id}");
						}
					],*/
					/*'addresses' => [
						'type' => Types::listOf(Types::address()),
						'description' => 'Address',
						'resolve' => function($root){
							return DB::select("SELECT u.*, a.* FROM addresses a LEFT JOIN users u ON u.id = a.user_id WHERE a.user_id = {$root->id}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
