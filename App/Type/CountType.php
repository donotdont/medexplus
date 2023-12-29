<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class CountType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Count Type',
			'fields' => function(){
				return [
					'count' => [
						'type' => Types::int(),
						'description' => 'COUNT(*)'
					],
					'date' => [
						'type' => Types::string(),
						'description' => 'Date'
					],
					'role' => [
						'type' => Types::string(),
						'description' => 'Role Name'
					],
				];
			}
		];
		parent::__construct($config);
	}
}
