<?php

namespace App\Model\Product\Exception;

use Exception;

class ProductNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('product not found');
    }
}
