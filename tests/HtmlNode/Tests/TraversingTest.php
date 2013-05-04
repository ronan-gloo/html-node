<?php
namespace HtmlNode\Tests;

use HtmlNode\Node;

class TaversingTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider getTraversingdata
	 */
	public function testFind($node, $single)
	{
		// Test that we can find something i children
		$this->assertInstanceOf("HtmlNode\Collection\Collection", $node->find("h2"));
		// Test exception
		try {
			$node->find([]);
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testClosest($node, $single)
	{
		// Test that we can find something in parents
		$this->assertInstanceOf("HtmlNode\Collection\Collection", $node->closest("h2"));
		
		// Test exception
		try {
			$node->closest([]);
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testChildren($node, $single)
	{
		// Test that we can find something in parents
		$this->assertInstanceOf("HtmlNode\Collection\Collection", $node->children());
		
		$childs = $node->children("h2");
		$this->assertInstanceOf("HtmlNode\Collection\Collection", $childs);
		
		// something found ?
		$this->assertNotNull($childs->get());
		
		// Is Node ?
		$this->assertInstanceOf(get_class($node), $childs->first());
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testParent($node, $single)
	{
		// No parent
		$this->assertNull($single->parent());
		// Parent is same
		$this->assertSame($node->children()->first()->parent(), $node);
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testIndex($node)
	{
		// Not in any collection
		$this->assertTrue($node->index());
		// Parent is same
		$this->assertEquals($node->children()->first()->index(), 0);
		$this->assertEquals($node->children()->last()->index(), $node->children()->length() - 1);
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testIsChildOf($node, $single)
	{
		$this->assertTrue($node->children()->first()->isChildOf($node));
		$this->assertFalse($node->isChildOf($single));
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testIsParentdOf($node, $single)
	{
		$child = $node->children()->last();
		$this->assertFalse($node->isParentOf($single));
		$this->assertTrue($node->isParentOf($child));
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testHasParent($node)
	{
		$this->assertTrue($node->children()->first()->hasParent());
		$this->assertFalse($node->hasParent());
	}

	/**
	 * @dataProvider getTraversingdata
	 */
	public function testHasChildren($node, $single)
	{
		$this->assertTrue($node->hasChildren());
		$this->assertFalse($single->hasChildren());
	}

	public function getTraversingdata()
	{
		$node = Node::make("div");
		
		foreach (range(1, 4) as $item) $node->append("h$item");
		
		return [[$node, Node::make("span")]];
	}
}

