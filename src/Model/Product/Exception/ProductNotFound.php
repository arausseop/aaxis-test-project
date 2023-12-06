<?php

namespace App\Model\Exception\Product;

use Exception;

class ProductNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('product not found');
    }
}
