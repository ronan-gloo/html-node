<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class TagTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getData
	 */
	public function testHasProperties($node)
	{
		$this->assertClassHasAttribute("tagname", get_class($node));
		$this->assertClassHasAttribute("autoclose", get_class($node));
		$this->assertClassHasAttribute("autoclosed", get_class($node));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testTag($node)
	{
		$this->assertEquals("div", $node->tag());
		
		// Test only striptags and trim
		$th = " <div>span </div> ";
		$this->assertEquals("span", $node->tag($th)->tag());
		
		try {
			$node->tag([]);
		}
		catch(\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
	}

	/**
	 * @dataProvider getData
	 */
	public function testAutoclose($node)
	{
		// Autoclose or not
		$this->assertfalse($node->autoclose());
		$this->assertTrue($node->tag("input")->autoclose());
	}
	
	public function getData()
	{
		return [[new Node("div")]];
	}
}