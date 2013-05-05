<?php

namespace HtmlNode\Tests;

use HtmlNode\Collection\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider getData
	 */
	public function testHasAttribute($col)
	{
		$this->assertClassHasAttribute('items', get_class($col));
	}

    /**
     * @dataProvider getData
     */
    public function testOffsetUnset($col)
    {
        $this->assertTrue($col->offsetUnset(2));
        $this->assertFalse($col->offsetUnset(-1));
        $this->assertNull($col->eq(2));
    }

    /**
     * @dataProvider getData
     */
    public function testOffset($col)
    {
        $this->assertFalse($col->offsetExists('dooo !'));
        $col->set('do');
        $this->assertTrue($col->offsetExists('do'));
    }

    /**
     * @dataProvider getData
     */
    public function testCount($col)
    {
        $this->assertEquals(count($col), $col->length());
    }

	/**
	 * @dataProvider getData
	 */
	public function testFirst($col, $data)
	{
		$this->assertSame($col->first(), reset($data));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testLast($col, $data)
	{
		$this->assertSame($col->last(), end($data));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testLength($col, $data)
	{
		$this->assertEquals($col->length(), count($data));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testGet($col, $data)
	{
		$this->assertSame($col->get(), $data);
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testEq($col, $data)
	{
		$this->assertSame($col->eq(0), $data[0]);
		$this->assertNull($col->eq(-1));
	}

	/**
	 * @dataProvider getData
	 */
	public function testIndexOf($col, $data)
	{
		$this->assertEquals($col->indexOf($data[4]), 4);
		$this->assertFalse($col->indexOf("huu"));
	}

	/**
	 * @dataProvider getData
	 */
	public function testSet($col)
	{
		$this->assertTrue($col->set("key", "val"));
		$this->assertSame("val", $col->eq("key"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testExchange($col, $data)
	{
		$this->assertSame($col, $col->exchange($data));
		$this->assertInstanceOf(get_class($col), $col->exchange($data));
	}

	/**
	 * @dataProvider getData
	 */
	public function testCopy($col)
	{
		$this->assertNotSame($col, $col->copy());
	}

	/**
	 * @dataProvider getData
	 */
	public function testAppend($col, $data)
	{
		$last = end($data);
		
		$this->assertTrue($col->append($last));
		$this->assertSame($last, $col->last());
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testPrepend($col, $data)
	{
		$first = reset($data);
		$this->assertTrue($col->prepend($first));
		$this->assertSame($first, $col->first());
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testInsertBefore($col, $data)
	{
		$insert = $data[3];
		// Index doesn't exists
		$this->assertFalse($col->insertBefore("new", $insert));
		// index exists
		$this->assertTrue($col->insertBefore($data[3], ["new"]));
		
		$this->assertEquals(3, $col->indexOf("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testInsertAfter($col, $data)
	{
		$insert = $data[3];
		// Index doesn't exists
		$this->assertFalse($col->insertAfter("new", $insert));
		// index exists
		$this->assertTrue($col->insertAfter($data[3], ["new"]));
		
		$this->assertEquals(4, $col->indexOf("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testInsert($col)
	{
		// Index doesn't exists
		$this->assertTrue($col->insert("new", -1));
		// index exists
		$this->assertTrue($col->insert("new", 3));
		
		$this->assertEquals(3, $col->indexOf("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testDelete($col)
	{
		$this->assertTrue($col->delete(2));
		$this->assertFalse($col->delete(-1));
		$this->assertNull($col->eq(2));
	}

	/**
	 * @dataProvider getData
	 */
	public function testRemove($col, $data)
	{
		$this->assertTrue($col->remove($data[2]));
		$this->assertFalse($col->remove("new"));
		$this->assertNull($col->search($data[2]));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testClear($col)
	{
		$this->assertTrue($col->clear());
		$this->assertEmpty($col->get());
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testHas($col, $data)
	{
		$this->assertTrue($col->has($data[1]));
		$this->assertFalse($col->has("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testOwn($col)
	{
		$this->assertTrue($col->own(1));
		$this->assertFalse($col->own("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testSearch($col, $data)
	{
		$this->assertSame($col->search($data[1]), $data[1]);
		$this->assertNull($col->search("new"));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testEach($col)
	{
		$return = $col->each(function($key, $val){
			return ($key === 2)  ? false : true;
		});
		$this->assertEquals($return, $col);
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testFilter($col)
	{
		$this->assertInstanceOf(get_class($col), $col->filter());
		$this->assertEmpty(
			$col->filter(function(){
				return false;
			})->get()
		);
	}
	
	public function getData()
	{		
		foreach (range(1, 40) as $val)
		{
			$item = new \stdClass();
			$item->name = "item.".$val;
			$data[] = $item;
		}
		$col = new Collection($data);
		return [[$col, $data]];
	}
	
}

