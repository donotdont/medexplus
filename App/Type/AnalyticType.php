<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class AnalyticType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Analytic Type',
			'fields' => function () {
				return [
					'id_analytic' => [
						'type' => Types::int(),
						'description' => 'Analytic ID'
					],
					'id_user' => [
						'type' => Types::int(),
						'description' => 'User ID'
					],
					'id_model' => [
						'type' => Types::int(),
						'description' => 'Model ID'
					],
					'model_name' => [
						'type' => Types::string(),
						'description' => 'Model Name'
					],
					'url' => [
						'type' => Types::string(),
						'description' => 'URL'
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
						'description' => 'Latitude'
					],
					'country' => [
						'type' => Types::string(),
						'description' => 'Country'
					],
					'operating_system' => [
						'type' => Types::string(),
						'description' => 'Operating System'
					],
					'browser' => [
						'type' => Types::string(),
						'description' => 'Browser'
					],
					'browser_version' => [
						'type' => Types::string(),
						'description' => 'Browser Version'
					],
					'created_at' => [
						'type' => Types::string(),
						'description' => 'Created At'
					],
					'user' => [
						'type' => Types::user(),
						'description' => 'User',
						'resolve' => function ($root) {
							return DB::selectOne("SELECT a.*, u.* FROM user u LEFT JOIN analytic a ON a.id_user = u.id_user WHERE a.id_analytic = {$root->id_analytic}");
						}
					],
					'model' => [
						'type' => Types::model(),
						'description' => 'User',
						'resolve' => function ($root) {
							$allow = ["job_new", "page", "post", "press_release", "student_activity", "study_tour"];
							if (empty($root->model_name) || in_array($root->model_name, $allow))
								return;

							if (!empty($root->id_model) && !empty($root->model_name) && $root->model_name != "admin2")
								return DB::selectOne("SELECT a.*, m.* FROM {$root->model_name} m LEFT JOIN analytic a ON a.id_model = m.id_{$root->model_name} WHERE a.id_analytic = {$root->id_analytic}");

							return;
						}
					],
				];
			}
		];
		parent::__construct($config);
	}
}
