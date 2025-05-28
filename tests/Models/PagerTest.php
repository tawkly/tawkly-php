<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use Tawkly\Models\Pager;
use Illuminate\Support\Collection;
use stdClass;

class PagerTest extends TestCase
{
    private array $collectionData = [
        ['id' => 1, 'name' => 'Item 1'],
        ['id' => 2, 'name' => 'Item 2'],
        ['id' => 3, 'name' => 'Item 3'],
    ];

    private stdClass $metaData;

    public function setUp(): void
    {
        parent::setUp();

        $this->metaData = (object) [
            'current_page' => 1,
            'last_page' => 3,
            'first_page' => 1,
            'next_page' => 2,
            'previous_page' => null,
            'cursors' => (object) [
                'after' => 'cursor_after_value',
                'before' => 'cursor_before_value',
            ],
        ];
    }

    private function getPrivateProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public function testNextWithCursors()
    {
        $mockMethodCallback = function ($param) {
            return new Pager([], (object) ['cursors' => (object) ['after' => $param]], function ($param) {
                return new Pager([], (object) ['cursors' => (object) ['after' => $param]], function ($param) {
                    return $param;
                });
            });
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $nextPager = $pager->next();

        $this->assertInstanceOf(Pager::class, $nextPager);

        $nextPagerMeta = $this->getPrivateProperty($nextPager, 'meta');

        $this->assertObjectHasProperty('cursors', $nextPagerMeta);
        $this->assertEquals('cursor_after_value', $nextPagerMeta->cursors->after);
    }

    public function testNextWithNextPage()
    {
        unset($this->metaData->cursors);

        $this->metaData->current_page = 1;
        $this->metaData->next_page = 2;

        $mockMethodCallback = function ($param) {
            return new Pager([], (object) ['current_page' => $param], function ($param) {
                return new Pager([], (object) ['current_page' => $param], function ($param) {
                    return $param;
                });
            });
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $nextPager = $pager->next();

        $this->assertInstanceOf(Pager::class, $nextPager);

        $nextPagerMeta = $this->getPrivateProperty($nextPager, 'meta');

        $this->assertObjectHasProperty('current_page', $nextPagerMeta);
        $this->assertEquals(2, $nextPagerMeta->current_page);
    }

    public function testNextLastPage()
    {
        unset($this->metaData->cursors);

        $this->metaData->current_page = 3;
        $this->metaData->next_page = null;

        $mockMethodCallback = function ($param) {
            return null;
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $nextPager = $pager->next();

        $this->assertNull($nextPager);
    }

    public function testPreviousWithCursors()
    {
        $mockMethodCallback = function ($param) {
            return new Pager([], (object) ['cursors' => (object) ['before' => $param]], function ($param) {
                return new Pager([], (object) ['cursors' => (object) ['before' => $param]], function ($param) {
                    return $param;
                });
            });
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $previousPager = $pager->previous();

        $this->assertInstanceOf(Pager::class, $previousPager);

        $previousPagerMeta = $this->getPrivateProperty($previousPager, 'meta');

        $this->assertObjectHasProperty('cursors', $previousPagerMeta);
        $this->assertEquals('cursor_before_value', $previousPagerMeta->cursors->before);
    }

    public function testPreviousWithPreviousPage()
    {
        unset($this->metaData->cursors);

        $this->metaData->current_page = 2;
        $this->metaData->previous_page = 1;

        $mockMethodCallback = function ($param) {
            return new Pager([], (object) ['current_page' => $param], function ($param) {
                return new Pager([], (object) ['current_page' => $param], function ($param) {
                    return $param;
                });
            });
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $previousPager = $pager->previous();

        $this->assertInstanceOf(Pager::class, $previousPager);

        $previousPagerMeta = $this->getPrivateProperty($previousPager, 'meta');

        $this->assertObjectHasProperty('current_page', $previousPagerMeta);
        $this->assertEquals(1, $previousPagerMeta->current_page);
    }

    public function testPreviousFirstPage()
    {
        unset($this->metaData->cursors);

        $this->metaData->current_page = 1;
        $this->metaData->previous_page = null;

        $mockMethodCallback = function ($param) {
            return null;
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $previousPager = $pager->previous();

        $this->assertNull($previousPager);
    }

    public function testItems()
    {
        $mockMethodCallback = function ($param) {
            return new Pager([], (object) [], function ($param) {
                return new Pager([], (object) [], function ($param) {
                    return $param;
                });
            });
        };

        $pager = new Pager($this->collectionData, $this->metaData, $mockMethodCallback);
        $items = $pager->items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals($this->collectionData, $items->toArray());
    }
}
