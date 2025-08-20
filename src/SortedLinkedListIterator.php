<?php

declare(strict_types=1);

namespace SortedLinkedList;

use Iterator;

/**
 * Lazy iterator for SortedLinkedList that doesn't create array copies.
 *
 * @template T of int|string
 * @implements Iterator<int, T>
 */
final class SortedLinkedListIterator implements Iterator
{
    /**
     * @var Node<T>|null
     */
    private $current;

    /**
     * @var Node<T>|null
     */
    private $head;

    private int $position = 0;

    /**
     * @param Node<T>|null $head
     */
    public function __construct(?Node $head)
    {
        $this->head = $head;
        $this->current = $head;
    }

    public function rewind(): void
    {
        $this->current = $this->head;
        $this->position = 0;
    }

    /**
     * @return T
     */
    public function current(): mixed
    {
        return $this->current->value;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->current = $this->current->next;
        $this->position++;
    }

    public function valid(): bool
    {
        return $this->current !== null;
    }
}