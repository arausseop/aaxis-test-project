<?php

namespace App\Model\Exception;

use Exception;

class DatabaseException extends Exception
{
    public static function createFromMessage(string $message): self
    {
        return new self(\sprintf('Database error. Message: %s', $message));
    }
}
