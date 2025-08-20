<?php

declare(strict_types=1);

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\InvalidTypeException;

/**
 * Test suite for InvalidTypeException.
 */
final class InvalidTypeExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new InvalidTypeException('integer', 'string');

        $this->assertSame(
            'Cannot insert string value into a list containing integer values',
            $exception->getMessage()
        );
    }

    public function testExceptionMessageReversed(): void
    {
        $exception = new InvalidTypeException('string', 'integer');

        $this->assertSame(
            'Cannot insert integer value into a list containing string values',
            $exception->getMessage()
        );
    }

    public function testExceptionIsInvalidArgumentException(): void
    {
        $exception = new InvalidTypeException('integer', 'string');

        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
    }
}
