<?php

declare(strict_types=1);

namespace App\Model\Exception;

use InvalidArgumentException as NativeInvalidArgumentException;

final class InvalidArgumentException extends NativeInvalidArgumentException
{
    public static function createFromMessage(string $message): self
    {
        return new static($message, 400);
    }

    public static function createFromArgument(string $argument): self
    {
        return new static(\sprintf('Invalid argument [%s]', $argument), 400);
    }

    public static function createFromNotExistArgument(string $argument): self
    {
        return new static(\sprintf('argument [%s] not exist in product', $argument), 400);
    }
}
