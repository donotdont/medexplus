<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class PermissionType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Permission',
			'fields' => function(){
				return [
					'id_permission' => [
						'type' => Types::int(),
						'description' => 'Permission ID'
					],
					'id_role' => [
						'type' => Types::int(),
						'description' => 'Role ID'
					],
					'model' => [
						'type' => Types::string(),
						'description' => 'Model Name'
					],
					'enable_code' => [
						'type' => Types::int(),
						'description' => 'Enable Code'
					],
					/*'users' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'Role -> User',
						'resolve' => function($root){
							return DB::select("SELECT u.*, r.* FROM user u LEFT JOIN role r ON r.id_role = u.id_role WHERE u.id_role = {$root->id_role}");
						}
					],*/
					'role' => [
						'type' => Types::role(),
						'description' => 'Role',
						'resolve' => function ($root) {
							if (!empty($root->id_role))
								return DB::selectOne("SELECT u.*, r.* FROM role r LEFT JOIN user u ON u.id_role = r.id_role WHERE u.id_role = {$root->id_role}");
							else
								return;
						}
					],
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
