<?php

namespace HtmlNode\Tests;

use HtmlNode\Collection;

class AttributeCollectionTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getData
	 */
	public function testFindRecursive($col, $data)
	{
		$this->assertNull($col->findRecursive("odd"));
		
		$this->assertEquals("subvalue2", $col->findRecursive("foo.level2.sublevel2"));
		$this->assertSame($data['foo'], $col->findRecursive("foo"));

	}
	/**
	 * @dataProvider getData
	 */
	public function testSetRecursive($col)
	{
		$this->assertTrue($col->setRecursive("foo.bar", "did"));
		$this->assertTrue($col->setRecursive(["foo" => ["bar" => "did"]]));
		$this->assertEquals("did", $col->findRecursive("foo.bar"));
	}
	
	public function getdata()
	{
		$data = [
			"foo" => [
				"level1" => "value1",
				"level2" => [
					"sublevel2" => "subvalue2"
				]
			],
			"bar" => "value"
		];
		
		return [[new Collection\Attribute($data), $data]];
	}
	
}