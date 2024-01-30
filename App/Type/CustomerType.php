<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class CustomerType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Customer',
			'fields' => function(){
				return [
					'id_customer' => [
						'type' => Types::int(),
						'description' => 'Customer ID'
					],
					'customer_name' => [
						'type' => Types::string(),
						'description' => 'Customer Name'
					],
					'customer_address' => [
						'type' => Types::string(),
						'description' => 'Customer Adress'
					],
					'customer_phone' => [
						'type' => Types::string(),
						'description' => 'Customer Phone'
					],
					/*'attributes' => [
						'type' => Types::attribute(),
						'description' => 'Attribute',
						'resolve' => function($root){
							return DB::selectOne("SELECT p.*, a.* FROM attribute a LEFT JOIN product p ON p.id_product = a.id_product WHERE p.id_product = {$root->id_product}");
						}
					]*/
				];
			}
		];
		parent::__construct($config);
	}
}
