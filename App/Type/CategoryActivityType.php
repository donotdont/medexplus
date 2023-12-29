<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class CategoryActivityType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Category Activity',
			'fields' => function(){
				return [
					'id_category_activity' => [
						'type' => Types::int(),
						'description' => 'Category Activity ID'
					],
					'name_th' => [
						'type' => Types::string(),
						'description' => 'Name Thai'
					],
					'name_en' => [
						'type' => Types::string(),
						'description' => 'Name English'
					],
					'subject_activities' => [
						'type' => Types::listOf(Types::subject_activity()),
						'description' => 'Category Activity -> Subject Activity',
						'resolve' => function($root){
							return DB::selectOne("SELECT sa.* FROM subject_activity sa LEFT JOIN category_activity ca ON ca.id_category_activity = sa.id_category_activity WHERE sa.id_category_activity = {$root->id_subject_activity}");//, ca.*
						}
					],
				];
			}
		];
		parent::__construct($config);
	}
}
