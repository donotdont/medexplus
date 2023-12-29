<?php

namespace App\Type\Scalar;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class EmailType extends ScalarType
{
    public function serialize($value)
    {
        return $value;
    }

    public function parseValue($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid Email');
        }

        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if (!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid Email');
        }

        return $valueNode->value;
    }
}
