<?php

declare(strict_types=1);

namespace SortedLinkedList;

/**
 * Interface for value comparison strategies.
 *
 * @template T of int|string
 */
interface ComparatorInterface
{
    /**
     * Compare two values.
     *
     * @param T $a
     * @param T $b
     * @return int Negative if $a < $b, 0 if equal, positive if $a > $b
     */
    public function compare($a, $b): int;
}