<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class SubjectActivityType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Subject Activity',
			'fields' => function () {
				return [
					'id_subject_activity' => [
						'type' => Types::int(),
						'description' => 'Subject Activity ID'
					],
					'id_category_activity' => [
						'type' => Types::int(),
						'description' => 'Category Activity ID'
					],
					'date_start' => [
						'type' => Types::string(),
						'description' => 'Date Start'
					],
					'date_end' => [
						'type' => Types::string(),
						'description' => 'Date End'
					],
					'name_th' => [
						'type' => Types::string(),
						'description' => 'Name Thai'
					],
					'name_en' => [
						'type' => Types::string(),
						'description' => 'Name English'
					],
					'credit' => [
						'type' => Types::int(),
						'description' => 'Credit'
					],
					'hours' => [
						'type' => Types::int(),
						'description' => 'Hours'
					],
					'category_activity' => [
						'type' => Types::category_activity(), //Types::listOf()
						'description' => 'Subject Activity -> Category Activity',
						'resolve' => function ($root) {
							return DB::selectOne("SELECT ca.* FROM category_activity ca LEFT JOIN subject_activity sa ON sa.id_category_activity = ca.id_category_activity WHERE ca.id_category_activity = {$root->id_category_activity}"); //, sa.*
						}
					],
				];
			}
		];
		parent::__construct($config);
	}
}
