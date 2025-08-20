<?php

declare(strict_types=1);

namespace SortedLinkedList;

/**
 * Node class for the sorted linked list.
 *
 * @template T of int|string
 * @internal
 */
final class Node
{
    /**
     * @var T
     */
    public $value;

    /**
     * @var Node<T>|null
     */
    public $next;

    /**
     * @param T $value
     * @param Node<T>|null $next
     */
    public function __construct($value, ?Node $next = null)
    {
        $this->value = $value;
        $this->next = $next;
    }
}
