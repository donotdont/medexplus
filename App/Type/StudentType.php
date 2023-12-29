<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class StudentType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Student',
			'fields' => function () {
				return [
					'id_student' => [
						'type' => Types::int(),
						'description' => 'Student ID'
					],
					'id_user' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'id_student_card' => [
						'type' => Types::string(),
						'description' => 'Student Card ID'
					],
					'id_card' => [
						'type' => Types::string(),
						'description' => 'Card ID'
					],
					'student_year' => [
						'type' => Types::int(),
						'description' => 'Student Year'
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
					'title_th' => [
						'type' => Types::string(),
						'description' => 'Title of Name Thai'
					],
					'firstname_th' => [
						'type' => Types::string(),
						'description' => 'First Name Thai'
					],
					'lastname_th' => [
						'type' => Types::string(),
						'description' => 'Last Name Thai'
					],
					'title_en' => [
						'type' => Types::string(),
						'description' => 'Title of Name English'
					],
					'firstname_en' => [
						'type' => Types::string(),
						'description' => 'First Name English'
					],
					'lastname_en' => [
						'type' => Types::string(),
						'description' => 'Last Name English'
					],
					'nickname' => [
						'type' => Types::string(),
						'description' => 'Nickname'
					],
					'major_code' => [
						'type' => Types::string(),
						'description' => 'Major Code'
					],
					'major' => [
						'type' => Types::string(),
						'description' => 'Major'
					],
					'student_status' => [
						'type' => Types::string(),
						'description' => 'Student Status'
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
					'avatar' => [
						'type' => Types::string(),
						'description' => 'Avatar'
					],
					'birthday' => [
						'type' => Types::string(),
						'description' => 'Birthday'
					],
					/*'role' => [
						'type' => Types::role(),
						'description' => 'Role',
						'resolve' => function ($root) {
							if (!empty($root->id_role))
								return DB::selectOne("SELECT u.*, r.* FROM role r LEFT JOIN student u ON u.id_role = r.id_role WHERE u.id_role = {$root->id_role}");
							else
								return;
						}
					],*/
					/*'addresses' => [
						'type' => Types::listOf(Types::address()),
						'description' => 'Address',
						'resolve' => function($root){
							return DB::select("SELECT u.*, a.* FROM addresses a LEFT JOIN students u ON u.id = a.student_id WHERE a.student_id = {$root->id}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
