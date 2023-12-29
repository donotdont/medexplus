<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class OnlineTrackingType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Online Tracking',
			'fields' => function () {
				return [
					'id_online_tracking' => [
						'type' => Types::int(),
						'description' => 'Online Tracking ID'
					],
					'id_online_tracking_status' => [
						'type' => Types::int(),
						'description' => 'Online Tracking Status ID'
					],
					'id_online_tracking_where' => [
						'type' => Types::int(),
						'description' => 'Online Tracking Whare ID'
					],
					'id_student' => [
						'type' => Types::int(),
						'description' => 'Student ID'
					],
					'id_student_card' => [
						'type' => Types::int(),
						'description' => 'Student Card ID'
					],
					'request_to' => [
						'type' => Types::string(),
						'description' => 'Request to'
					],
					'date_start' => [
						'type' => Types::string(),
						'description' => 'Date Start'
					],
					'due_date' => [
						'type' => Types::string(),
						'description' => 'Due Date'
					],
					'duration' => [
						'type' => Types::int(),
						'description' => 'Duration'
					],
					'note' => [
						'type' => Types::string(),
						'description' => 'Note'
					],
					'ip' => [
						'type' => Types::string(),
						'description' => 'IP'
					],
					/*'category_activity' => [
						'type' => Types::category_activity(), //Types::listOf()
						'description' => 'Subject Activity -> Category Activity',
						'resolve' => function ($root) {
							return DB::selectOne("SELECT ca.* FROM category_activity ca LEFT JOIN subject_activity sa ON sa.id_category_activity = ca.id_category_activity WHERE ca.id_category_activity = {$root->id_category_activity}"); //, sa.*
						}
					],*/
				];
			}
		];
		parent::__construct($config);
	}
}
