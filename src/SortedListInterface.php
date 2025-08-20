<?php

declare(strict_types=1);

namespace SortedLinkedList;

use Countable;
use IteratorAggregate;

/**
 * Interface for a sorted list that maintains elements in sorted order.
 *
 * @template T of int|string
 * @extends IteratorAggregate<int, T>
 */
interface SortedListInterface extends Countable, IteratorAggregate
{
    /**
     * Insert a value into the sorted list.
     *
     * @param int|string $value
     * @throws InvalidTypeException if value type doesn't match existing elements
     */
    public function insert($value): void;

    /**
     * Remove a value from the sorted list.
     *
     * @param int|string $value
     * @return bool True if value was found and removed, false otherwise
     */
    public function remove($value): bool;

    /**
     * Check if a value exists in the sorted list.
     *
     * @param int|string $value
     * @return bool True if value exists, false otherwise
     */
    public function contains($value): bool;

    /**
     * Convert the sorted list to an array.
     *
     * @return array<T>
     */
    public function toArray(): array;

    /**
     * Check if the sorted list is empty.
     */
    public function isEmpty(): bool;

    /**
     * Remove all elements from the sorted list.
     */
    public function clear(): void;

    /**
     * Get the first (minimum) value in the sorted list.
     *
     * @return int|string|null First value or null if empty
     */
    public function first();

    /**
     * Get the last (maximum) value in the sorted list.
     *
     * @return int|string|null Last value or null if empty
     */
    public function last();
}
