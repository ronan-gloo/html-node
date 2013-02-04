<?php
namespace HtmlNode\Tests;

use HtmlNode\Node, HtmlNode\Util\Finder;

class FinderTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getData
	 */
	public function testAttribute($finder)
	{
		$this->assertClassHasAttribute('node', get_class($finder));
		$this->assertClassHasAttribute('results', get_class($finder));
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testResult($finder, $ins)
	{
		$this->assertInstanceOf($ins, $finder->result());
	}
	
	/**
	 * @dataProvider getData
	 */
	public function testParents($f, $ins, $child)
	{
		$finder = new Finder($child);
		$this->assertNotEmpty($finder->parents("section")->get());

		$finder = new Finder($child);
		$this->assertEmpty($finder->parents("section", false)->get());
	}
	/**
	 * @dataProvider getData
	 */
	public function testChildren($f)
	{
		$this->assertEmpty($f->children("li")->get());
		$this->assertNotEmpty($f->children("h1")->get());
	}
	
	public function getData()
	{
		$parent = Node::make('section');
		$node		= Node::make("div")->appendTo($parent);
		foreach (range(1,5) as $v) $node->append("h$v");
		
		$colInstance = 'HtmlNode\Collection\Collection';
				
		return [[new Finder($parent), $colInstance, $node->children()->first()]];
	}
}