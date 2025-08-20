<?php

declare(strict_types=1);

namespace SortedLinkedList;

/**
 * Interface for type validation strategies.
 */
interface TypeValidatorInterface
{
    /**
     * Validate that a value can be inserted into the list.
     *
     * @param int|string $value
     * @throws InvalidTypeException if value type is incompatible
     */
    public function validate($value): void;

    /**
     * Reset the validator after all elements are removed.
     */
    public function reset(): void;
}