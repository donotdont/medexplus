<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class UserType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'User',
			'fields' => function () {
				return [
					'id_user' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'id_role' => [
						'type' => Types::int(),
						'description' => 'Role ID'
					],
					'username' => [
						'type' => Types::string(),
						'description' => 'Username'
					],
					'email' => [
						'type' => Types::string(),
						'description' => 'E-mail'
					],
					'phone' => [
						'type' => Types::string(),
						'description' => 'Phone'
					],
					'password' => [
						'type' => Types::string(),
						'description' => 'Password'
					],
					'firstname' => [
						'type' => Types::string(),
						'description' => 'First Name'
					],
					'lastname' => [
						'type' => Types::string(),
						'description' => 'Last Name'
					],
					'nickname' => [
						'type' => Types::string(),
						'description' => 'Nickname'
					],
					'confirmed' => [
						'type' => Types::int(),
						'description' => 'Confirmed'
					],
					'active' => [
						'type' => Types::int(),
						'description' => 'Active'
					],
					'block' => [
						'type' => Types::int(),
						'description' => 'Block'
					],
					'ip_address' => [
						'type' => Types::string(),
						'description' => 'IP Address'
					],
					'latitude' => [
						'type' => Types::float(),
						'description' => 'Latitude'
					],
					'longitude' => [
						'type' => Types::float(),
						'description' => 'Longitude'
					],
					'avatar' => [
						'type' => Types::string(),
						'description' => 'Avatar'
					],
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
