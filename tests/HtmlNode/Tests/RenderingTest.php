<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class RenderingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider getData
	 */
	public function testRender($node)
	{
		$this->assertTrue(is_string($node->render()));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testHtml($node, $parent, $extra)
	{
		// no childs: nothing
		$this->assertEmpty($node->html());
		
		$expected = str_repeat("<div></div>", 2);
		$this->assertEquals($expected, $parent->html());
				
		// Test with node
		$this->assertEquals($node->render(), $extra->html($node)->html());
		
		// Test with string
		$html = $node->render();
		$this->assertEquals($node->render(), $extra->html($html)->html());
		
		$childs = clone $node->children();
		
		// Test with collection
		$this->assertEquals($childs, $node->html($childs)->children());
		
		// Test returns $this
		$this->assertSame($node, $node->html('<div>'));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testContents($node)
	{
		$this->assertContains("Hello World", $node->contents());
	}

	/**
	 * @dataProvider getData
	 */
	public function testSelf($node)
	{
		$this->assertEquals('<div class="one two">Hello World</div>', $node->self());
	}
	
	
	public function getData()
	{
		$node1 = Node::make("div", "Hello World", ["class" => ["one", "two"]]);
		$node2 = clone $node1;
		$node3 = clone $node1;
		
		foreach (range(1, 2) as $i) $node2->append("div");
		
		return [[$node1, $node2, $node3]];
	}
}