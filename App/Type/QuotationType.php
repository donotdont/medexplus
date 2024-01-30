<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;

class QuotationType extends ObjectType
{
	public function __construct()
	{
		$config = [
			'description' => 'Quotation',
			'fields' => function(){
				return [
					'id_quotation' => [
						'type' => Types::int(),
						'description' => 'Quotation ID'
					],
					'id_product' => [
						'type' => Types::int(),
						'description' => 'Product ID'
					],
					'quotation_quantity' => [
						'type' => Types::int(),
						'description' => 'Quotation Quantity'
					],
					'quotation_customer_name' => [
						'type' => Types::string(),
						'description' => 'Quotation Customer Name'
					],
					'quotation_customer_address' => [
						'type' => Types::string(),
						'description' => 'Quotation Customer Address'
					],
					'quotation_customer_phone' => [
						'type' => Types::string(),
						'description' => 'Quotation Customer Phone'
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
