<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class MenuTopType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Menu Top',
			'fields' => function () {
				return [
					'id_menu_top' => [
						'type' => Types::int(),
						'description' => 'Menu Top ID'
					],
					'id_model' => [
						'type' => Types::int(),
						'description' => 'Model ID'
					],
					'model_name' => [
						'type' => Types::string(),
						'description' => 'Model Name'
					],
					'order_index' => [
						'type' => Types::int(),
						'description' => 'Order Index'
					],
					'model' => [
						'type' => Types::model(),
						'description' => 'Page',
						'resolve' => function ($root) {
							if (!empty($root->model_name)){
								return DB::selectOne("SELECT mt.*, p.* FROM menu_top mt LEFT JOIN {$root->model_name} p ON p.id_{$root->model_name} = mt.id_model WHERE mt.id_menu_top = {$root->id_menu_top}");
							}

							return [];
						}
					]
				];
			}
		];
		parent::__construct($config);
	}
}
