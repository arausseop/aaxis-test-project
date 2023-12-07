<?php

declare(strict_types=1);

namespace App\Model\Exception;

use InvalidArgumentException as NativeInvalidArgumentException;

final class InvalidArgumentException extends NativeInvalidArgumentException
{
    public static function createFromMessage(string $message): self
    {
        return new static($message);
    }

    public static function createFromArgument(string $argument): self
    {
        return new static(\sprintf('Invalid argument [%s]', $argument));
    }

    public static function createFromNotExistArgument(string $argument): self
    {
        return new static(\sprintf('argument [%s] not exist in product', $argument));
    }
}
