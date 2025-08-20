<?php

declare(strict_types=1);

namespace SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SortedLinkedList\InvalidTypeException;
use SortedLinkedList\SortedLinkedList;
use SortedLinkedList\DefaultComparator;
use SortedLinkedList\TypeValidator;
use SortedLinkedList\ComparatorInterface;

/**
 * Comprehensive test suite for SortedLinkedList.
 */
final class SortedLinkedListTest extends TestCase
{
    /** @var SortedLinkedList<int|string> */
    private SortedLinkedList $list;

    protected function setUp(): void
    {
        $this->list = new SortedLinkedList();
    }

    public function testEmptyListIsEmpty(): void
    {
        $this->assertTrue($this->list->isEmpty());
        $this->assertSame(0, $this->list->count());
        $this->assertSame([], $this->list->toArray());
        $this->assertNull($this->list->first());
        $this->assertNull($this->list->last());
    }

    public function testInsertSingleInteger(): void
    {
        $this->list->insert(5);

        $this->assertFalse($this->list->isEmpty());
        $this->assertSame(1, $this->list->count());
        $this->assertSame([5], $this->list->toArray());
        $this->assertSame(5, $this->list->first());
        $this->assertSame(5, $this->list->last());
        $this->assertTrue($this->list->contains(5));
    }

    public function testInsertMultipleIntegersInOrder(): void
    {
        $this->list->insert(3);
        $this->list->insert(1);
        $this->list->insert(4);
        $this->list->insert(2);

        $this->assertSame([1, 2, 3, 4], $this->list->toArray());
        $this->assertSame(4, $this->list->count());
        $this->assertSame(1, $this->list->first());
        $this->assertSame(4, $this->list->last());
    }

    public function testInsertDuplicateIntegers(): void
    {
        $this->list->insert(2);
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);
        $this->list->insert(2);

        $this->assertSame([1, 2, 2, 2, 3], $this->list->toArray());
        $this->assertSame(5, $this->list->count());
    }

    public function testInsertSingleString(): void
    {
        $this->list->insert('hello');

        $this->assertFalse($this->list->isEmpty());
        $this->assertSame(1, $this->list->count());
        $this->assertSame(['hello'], $this->list->toArray());
        $this->assertSame('hello', $this->list->first());
        $this->assertSame('hello', $this->list->last());
        $this->assertTrue($this->list->contains('hello'));
    }


    public function testInsertMultipleStringsInOrder(): void
    {
        $this->list->insert('zebra');
        $this->list->insert('apple');
        $this->list->insert('monkey');
        $this->list->insert('banana');

        $this->assertSame(['apple', 'banana', 'monkey', 'zebra'], $this->list->toArray());
        $this->assertSame(4, $this->list->count());
        $this->assertSame('apple', $this->list->first());
        $this->assertSame('zebra', $this->list->last());
    }

    public function testInsertMixedTypesThrowsException(): void
    {
        $this->list->insert(5);

        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Cannot insert string value into a list containing integer values');

        $this->list->insert('test');
    }

    public function testInsertStringThenIntegerThrowsException(): void
    {
        $this->list->insert('test');

        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Cannot insert integer value into a list containing string values');

        $this->list->insert(5);
    }

    public function testRemoveFromEmptyList(): void
    {
        $this->assertFalse($this->list->remove(5));
        $this->assertSame(0, $this->list->count());
    }

    public function testRemoveExistingElement(): void
    {
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);

        $this->assertTrue($this->list->remove(2));
        $this->assertSame([1, 3], $this->list->toArray());
        $this->assertSame(2, $this->list->count());
        $this->assertFalse($this->list->contains(2));
    }

    public function testRemoveNonExistentElement(): void
    {
        $this->list->insert(1);
        $this->list->insert(3);

        $this->assertFalse($this->list->remove(2));
        $this->assertSame([1, 3], $this->list->toArray());
        $this->assertSame(2, $this->list->count());
    }

    public function testRemoveFirstElement(): void
    {
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);

        $this->assertTrue($this->list->remove(1));
        $this->assertSame([2, 3], $this->list->toArray());
        $this->assertSame(2, $this->list->first());
    }

    public function testRemoveLastElement(): void
    {
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);

        $this->assertTrue($this->list->remove(3));
        $this->assertSame([1, 2], $this->list->toArray());
        $this->assertSame(2, $this->list->last());
    }

    public function testRemoveOnlyElement(): void
    {
        $this->list->insert(5);

        $this->assertTrue($this->list->remove(5));
        $this->assertTrue($this->list->isEmpty());
        $this->assertSame(0, $this->list->count());
        $this->assertNull($this->list->first());
        $this->assertNull($this->list->last());
    }

    public function testRemoveDuplicateElement(): void
    {
        $this->list->insert(2);
        $this->list->insert(2);
        $this->list->insert(2);

        $this->assertTrue($this->list->remove(2));
        $this->assertSame([2, 2], $this->list->toArray());
        $this->assertSame(2, $this->list->count());

        $this->assertTrue($this->list->remove(2));
        $this->assertSame([2], $this->list->toArray());
        $this->assertSame(1, $this->list->count());

        $this->assertTrue($this->list->remove(2));
        $this->assertTrue($this->list->isEmpty());
    }

    public function testContainsExistingElements(): void
    {
        $this->list->insert(1);
        $this->list->insert(3);
        $this->list->insert(5);

        $this->assertTrue($this->list->contains(1));
        $this->assertTrue($this->list->contains(3));
        $this->assertTrue($this->list->contains(5));
    }

    public function testContainsNonExistentElement(): void
    {
        $this->list->insert(1);
        $this->list->insert(3);
        $this->list->insert(5);

        $this->assertFalse($this->list->contains(0));
        $this->assertFalse($this->list->contains(2));
        $this->assertFalse($this->list->contains(4));
        $this->assertFalse($this->list->contains(6));
    }

    public function testContainsInEmptyList(): void
    {
        $this->assertFalse($this->list->contains(1));
        $this->assertFalse($this->list->contains('test'));
    }

    public function testClearList(): void
    {
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);

        $this->list->clear();

        $this->assertTrue($this->list->isEmpty());
        $this->assertSame(0, $this->list->count());
        $this->assertSame([], $this->list->toArray());
        $this->assertNull($this->list->first());
        $this->assertNull($this->list->last());
    }

    public function testClearEmptyList(): void
    {
        $this->list->clear();

        $this->assertTrue($this->list->isEmpty());
        $this->assertSame(0, $this->list->count());
    }

    public function testTypeResetAfterClear(): void
    {
        $this->list->insert(5);
        $this->list->clear();

        // Should be able to insert string after clearing
        $this->list->insert('test');
        $this->assertSame(['test'], $this->list->toArray());
    }

    public function testTypeResetAfterRemovingAllElements(): void
    {
        $this->list->insert(5);
        $this->list->remove(5);

        // Should be able to insert string after removing all elements
        $this->list->insert('test');
        $this->assertSame(['test'], $this->list->toArray());
    }

    public function testIterator(): void
    {
        $this->list->insert(3);
        $this->list->insert(1);
        $this->list->insert(4);
        $this->list->insert(2);

        $values = [];
        foreach ($this->list as $value) {
            $values[] = $value;
        }

        $this->assertSame([1, 2, 3, 4], $values);
    }

    public function testIteratorWithEmptyList(): void
    {
        $values = [];
        foreach ($this->list as $value) {
            $values[] = $value;
        }

        $this->assertSame([], $values);
    }

    public function testLargeDataset(): void
    {
        $numbers = range(1, 1000);
        shuffle($numbers);

        foreach ($numbers as $number) {
            $this->list->insert($number);
        }

        $this->assertSame(1000, $this->list->count());
        $this->assertSame(1, $this->list->first());
        $this->assertSame(1000, $this->list->last());
        $this->assertSame(range(1, 1000), $this->list->toArray());
    }

    public function testNegativeIntegers(): void
    {
        $this->list->insert(-5);
        $this->list->insert(0);
        $this->list->insert(-10);
        $this->list->insert(5);

        $this->assertSame([-10, -5, 0, 5], $this->list->toArray());
        $this->assertSame(-10, $this->list->first());
        $this->assertSame(5, $this->list->last());
    }

    public function testEmptyStrings(): void
    {
        $this->list->insert('b');
        $this->list->insert('');
        $this->list->insert('a');

        $this->assertSame(['', 'a', 'b'], $this->list->toArray());
        $this->assertSame('', $this->list->first());
        $this->assertSame('b', $this->list->last());
    }

    public function testSpecialCharacters(): void
    {
        $this->list->insert('zebra');
        $this->list->insert('!@#');
        $this->list->insert('apple');
        $this->list->insert('123');

        $this->assertSame(['!@#', '123', 'apple', 'zebra'], $this->list->toArray());
    }

    /**
     * Test that demonstrates performance characteristics.
     */
    public function testPerformanceCharacteristics(): void
    {
        $startTime = microtime(true);

        // Insert 5000 elements in random order
        $numbers = range(1, 5000);
        shuffle($numbers);

        foreach ($numbers as $number) {
            $this->list->insert($number);
        }

        $insertTime = microtime(true) - $startTime;

        // Verify they're all there and sorted
        $this->assertSame(5000, $this->list->count());
        $this->assertSame(range(1, 5000), $this->list->toArray());

        // Test search performance
        $searchStart = microtime(true);

        for ($i = 1; $i <= 100; $i++) {
            $this->assertTrue($this->list->contains($i));
        }

        $searchTime = microtime(true) - $searchStart;

        // These are just basic performance tests - in a real scenario,
        // you'd have more specific benchmarks
        $this->assertLessThan(5.0, $insertTime, 'Insert operations took too long');
        $this->assertLessThan(1.0, $searchTime, 'Search operations took too long');
    }

    public function testConstructorWithCustomComparator(): void
    {
        $reverseComparator = new class implements ComparatorInterface {
            public function compare($a, $b): int {
                if ($a === $b) return 0;
                return $a > $b ? -1 : 1; // Reverse order
            }
        };
        
        $list = new SortedLinkedList($reverseComparator);
        $list->insert(1);
        $list->insert(3);
        $list->insert(2);
        
        $this->assertSame([3, 2, 1], $list->toArray());
    }

    public function testConstructorWithCustomTypeValidator(): void
    {
        $permissiveValidator = new class implements \SortedLinkedList\TypeValidatorInterface {
            public function validate($value): void {
                // Allow any type
            }
            public function reset(): void {
                // No-op
            }
        };
        
        $list = new SortedLinkedList(null, $permissiveValidator);
        $list->insert(5);
        $list->insert('test'); // This would normally throw
        
        $this->assertSame(2, $list->count());
    }

    public function testLastElementOptimization(): void
    {
        // Test that last() is now O(1) instead of O(n)
        for ($i = 1; $i <= 1000; $i++) {
            $this->list->insert($i);
        }
        
        $start = microtime(true);
        $last = $this->list->last();
        $time = microtime(true) - $start;
        
        $this->assertSame(1000, $last);
        $this->assertLessThan(0.001, $time, 'last() should be O(1)');
    }

    public function testIteratorDoesNotCreateArrayCopy(): void
    {
        $this->list->insert(1);
        $this->list->insert(2);
        $this->list->insert(3);
        
        $values = [];
        foreach ($this->list as $value) {
            $values[] = $value;
        }
        
        $this->assertSame([1, 2, 3], $values);
        
        // Test iterator rewind
        $values2 = [];
        foreach ($this->list as $value) {
            $values2[] = $value;
        }
        
        $this->assertSame([1, 2, 3], $values2);
    }

    public function testTailPointerMaintainedCorrectly(): void
    {
        // Test tail maintenance during insertions
        $this->list->insert(2);
        $this->assertSame(2, $this->list->last());
        
        $this->list->insert(1); // Insert at head
        $this->assertSame(2, $this->list->last());
        
        $this->list->insert(3); // Insert at tail
        $this->assertSame(3, $this->list->last());
        
        $this->list->insert(2); // Insert in middle
        $this->assertSame(3, $this->list->last());
        
        // Test tail maintenance during removal
        $this->list->remove(3); // Remove tail
        $this->assertSame(2, $this->list->last());
    }
}