<?php

declare(strict_types=1);

namespace SortedLinkedList;

/**
 * Default type validator that ensures homogeneous types.
 */
final class TypeValidator implements TypeValidatorInterface
{
    /**
     * @var 'integer'|'string'|null
     */
    private $expectedType = null;

    /**
     * @param int|string $value
     */
    public function validate($value): void
    {
        $valueType = gettype($value);

        if ($this->expectedType === null) {
            $this->expectedType = $valueType;
            return;
        }

        if ($this->expectedType !== $valueType) {
            throw new InvalidTypeException($this->expectedType, $valueType);
        }
    }

    public function reset(): void
    {
        $this->expectedType = null;
    }
}