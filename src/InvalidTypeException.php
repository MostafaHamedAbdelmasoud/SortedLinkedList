<?php

declare(strict_types=1);

namespace SortedLinkedList;

use InvalidArgumentException;

/**
 * Exception thrown when trying to insert a value with incompatible type.
 */
class InvalidTypeException extends InvalidArgumentException
{
    public function __construct(string $expectedType, string $actualType)
    {
        parent::__construct(
            sprintf(
                'Cannot insert %s value into a list containing %s values',
                $actualType,
                $expectedType
            )
        );
    }
}
