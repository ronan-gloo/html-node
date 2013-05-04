<?php
namespace HtmlNode\Tests;

use HtmlNode\Node;

class SeekTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testPrev($first, $second)
	{
		$this->assertNull($first->prev());
		$this->assertNull(Node::make()->prev());
		$this->assertSame($second->prev(), $first);
	}

	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testNext($first, $second, $last)
	{
		$this->assertNull($last->next());
		$this->assertNull(Node::make()->next());
		$this->assertSame($second->next(), $last);
	}

	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testPrevAll($first, $second, $last)
	{
		$this->assertInstanceOf("HtmlNode\\Collection\\Collection", $first->prevAll());
		$this->assertEmpty($first->prevAll()->get());
		$this->assertEmpty(Node::make()->prevAll()->get());
		$this->assertSame($last->prevAll()->get(), [$first, $second]);
	}
	
	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testNextAll($first, $second, $last)
	{
		$this->assertInstanceOf("HtmlNode\\Collection\\Collection", $first->nextAll());
		$this->assertEmpty($last->nextAll()->get());
		$this->assertEmpty(Node::make()->nextAll()->get());
		$this->assertSame($first->nextAll()->get(), [$second, $last]);
	}
	
	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testSiblings($first, $second, $last)
	{
		$this->assertInstanceOf("HtmlNode\\Collection\\Collection", $first->siblings());
		$this->assertEmpty(Node::make()->siblings()->get());
		
		$items = $second->siblings()->get();
		
		$this->assertContains($first, $items);
		$this->assertContains($last, $items);
	}
	/**
	 * @dataProvider getChildrenTests 
	 */
	public function testFilterSeek($first, $second, $last)
	{
		$this->assertContains($first, $last->prevAll("h1")->get());
		$this->assertEmpty($second->prevAll("div")->get());
	}
	
  public function getChildrenTests()
  {
  	foreach (range(1, 3) as $num) $nodes[] = Node::make("h$num");
  	
  	$node = Node::make("hgroup")->html($nodes);

    return [$nodes];
  }

}

