<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class ScannerStudentType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Scanner Student',
			'fields' => function () {
				return [
					'id_scanner_student' => [
						'type' => Types::int(),
						'description' => 'Scanner Student ID'
					],
					'id_user' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'id_subject_activity' => [
						'type' => Types::int(),
						'description' => 'Subject Activity ID'
					],
					'id_student' => [
						'type' => Types::int(),
						'description' => 'Student ID'
					],
					'created_at' => [
						'type' => Types::string(),
						'description' => 'Created at'
					],
					'updated_at' => [
						'type' => Types::string(),
						'description' => 'Updated at'
					],
					'result' => [
						'type' => Types::string(),
						'description' => 'Result'
					],
					'subject_activity' => [
						'type' => Types::subject_activity(), //Types::listOf()
						'description' => 'Scanner Student -> Subject Activity',
						'resolve' => function ($root) {
							return DB::selectOne("SELECT sa.* FROM subject_activity sa LEFT JOIN scanner_student ss ON ss.id_subject_activity = sa.id_subject_activity WHERE sa.id_subject_activity = {$root->id_subject_activity}"); //, ss.*
						}
					],
					'user' => [
						'type' => Types::user(), //Types::listOf()
						'description' => 'Scanner Student -> User',
						'resolve' => function ($root) {
							if (!empty($root->id_user))
								return DB::selectOne("SELECT u.* FROM user u LEFT JOIN scanner_student ss ON ss.id_user = u.id_user WHERE ss.id_user = {$root->id_user}"); //, ss.*
							else
								return;
						}
					],
					'student' => [
						'type' => Types::student(), //Types::listOf()
						'description' => 'Scanner Student -> Student',
						'resolve' => function ($root) {
							if (!empty($root->id_student))
								return DB::selectOne("SELECT s.* FROM student s LEFT JOIN scanner_student ss ON ss.id_student = s.id_student WHERE ss.id_student = {$root->id_student}"); //, ss.*
							else
								return;
						}
					],
					/*'category_activity' => [
						'type' => Types::category_activity(), //Types::listOf()
						'description' => 'Scanner Student -> Category Activity',
						'resolve' => function($root){
							return DB::selectOne("SELECT ca.* FROM category_activity ca LEFT JOIN scanner_student sa ON sa.id_category_activity = ca.id_category_activity WHERE ca.id_category_activity = {$root->id_category_activity}");//, sa.*
						}
					],*/
				];
			}
		];
		parent::__construct($config);
	}
}
