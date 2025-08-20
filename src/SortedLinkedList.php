<?php

declare(strict_types=1);

namespace SortedLinkedList;

use Iterator;

/**
 * A sorted linked list implementation that maintains elements in sorted order.
 * Can contain either integers or strings, but not both.
 * 
 * Optimizations:
 * - Tail pointer for O(1) access to last element
 * - Dependency injection for comparator and type validator
 * - Lazy iterator that doesn't create array copies
 *
 * @template T of int|string
 * @implements SortedListInterface<T>
 */
final class SortedLinkedList implements SortedListInterface
{
    /**
     * @var Node<T>|null
     */
    private ?Node $head = null;

    /**
     * @var Node<T>|null
     */
    private ?Node $tail = null;

    private int $count = 0;

    /**
     * @var ComparatorInterface<T>
     */
    private ComparatorInterface $comparator;

    private TypeValidatorInterface $typeValidator;

    /**
     * @param ComparatorInterface<T>|null $comparator
     */
    public function __construct(
        ?ComparatorInterface $comparator = null,
        ?TypeValidatorInterface $typeValidator = null
    ) {
        $this->comparator = $comparator ?? new DefaultComparator();
        $this->typeValidator = $typeValidator ?? new TypeValidator();
    }

    /**
     * @param int|string $value
     */
    public function insert($value): void
    {
        $this->typeValidator->validate($value);

        /** @var Node<T> $newNode */
        $newNode = new Node($value);

        if ($this->head === null) {
            $this->head = $this->tail = $newNode;
        } elseif ($this->comparator->compare($value, $this->head->value) < 0) {
            $newNode->next = $this->head;
            $this->head = $newNode;
        } elseif ($this->comparator->compare($value, $this->tail->value) >= 0) {
            $this->tail->next = $newNode;
            $this->tail = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null && $this->comparator->compare($value, $current->next->value) >= 0) {
                $current = $current->next;
            }
            $newNode->next = $current->next;
            $current->next = $newNode;
        }

        $this->count++;
    }

    /**
     * @param int|string $value
     */
    public function remove($value): bool
    {
        if ($this->head === null) {
            return false;
        }

        if ($this->comparator->compare($this->head->value, $value) === 0) {
            $this->head = $this->head->next;
            if ($this->head === null) {
                $this->tail = null;
            }
            $this->count--;
            $this->updateTypeAfterRemoval();
            return true;
        }

        $current = $this->head;
        while ($current->next !== null) {
            if ($this->comparator->compare($current->next->value, $value) === 0) {
                $nodeToRemove = $current->next;
                $current->next = $nodeToRemove->next;
                if ($nodeToRemove === $this->tail) {
                    $this->tail = $current;
                }
                $this->count--;
                $this->updateTypeAfterRemoval();
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * @param int|string $value
     */
    public function contains($value): bool
    {
        $current = $this->head;
        while ($current !== null) {
            $comparison = $this->comparator->compare($current->value, $value);
            if ($comparison === 0) {
                return true;
            }
            if ($comparison > 0) {
                break;
            }
            $current = $current->next;
        }
        return false;
    }

    /**
     * @return array<T>
     */
    public function toArray(): array
    {
        /** @var array<T> $array */
        $array = [];
        $current = $this->head;
        while ($current !== null) {
            $array[] = $current->value;
            $current = $current->next;
        }
        return $array;
    }

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    public function clear(): void
    {
        $this->head = null;
        $this->tail = null;
        $this->count = 0;
        $this->typeValidator->reset();
    }

    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return int|string|null
     */
    public function first()
    {
        return $this->head?->value;
    }

    /**
     * @return int|string|null
     */
    public function last()
    {
        return $this->tail?->value;
    }

    /**
     * @return Iterator<int, T>
     */
    public function getIterator(): Iterator
    {
        return new SortedLinkedListIterator($this->head);
    }

    private function updateTypeAfterRemoval(): void
    {
        if ($this->count === 0) {
            $this->typeValidator->reset();
        }
    }
}
