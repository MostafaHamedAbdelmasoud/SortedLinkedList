<?php

declare(strict_types=1);

namespace SortedLinkedList;

/**
 * Default comparator for integers and strings.
 *
 * @template T of int|string
 * @implements ComparatorInterface<T>
 */
final class DefaultComparator implements ComparatorInterface
{
    /**
     * @param T $a
     * @param T $b
     */
    public function compare($a, $b): int
    {
        if ($a === $b) {
            return 0;
        }
        return $a < $b ? -1 : 1;
    }
}