<?php
namespace HtmlNode\Tests;

use HtmlNode\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
	
	public function testProperties()
	{
		$this->assertClassHasAttribute("text", "HtmlNode\\Node");
	}
	
	public function testInstance()
	{
		$this->assertInstanceOf("HtmlNode\\Node", Node::make());
	}

	public function testConstructor()
	{
		$node = Node::make("div", "foo", ["class" => "test"]);
		
		$this->assertEquals($node->tag(), "div");
		$this->assertEquals($node->text(), "foo");
		$this->assertEquals($node->attr("class"), ["test"]);
	}
	
	/**
	 * @dataProvider getNodeTests
	 */
	public function testTag($d)
	{
		$this->assertSame("div", $d->tag());
	}
	
	public function testUnregisteredMacro()
	{
		try {
			Node::foo();
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("\\BadMethodCallException", $e);
		}
	}
	/**
	 * @dataProvider getMacroTests
	 */
	public function testRegisteredMacro($i1, $i2, $s1, $s2)
	{
  	$this->assertInstanceOf("HtmlNode\\Node", Node::instance());
  	
  	// Test instances
  	$this->assertNotSame($i1, $i2);
  	
  	// Test singletons
  	$this->assertSame($s1, $s2);
	}

	/**
	 * @dataProvider getMacroTests
	 */
	public function testCreateMacro()
	{
	  $data = Node::macro("foo", function(){ return Node::make("input"); });

  	$this->assertNull($data);
	}

	/**
	 * @dataProvider getNodeTests
	 */
	public function testDepencies($d)
	{
		$this->assertInstanceOf("HtmlNode\\Dependency\\Node", $d->text);
		
		try {
			$d->foo;
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("\\OutOfBoundsException", $e);
		}
	}
	
	/**
	 * @dataProvider getNodeTests
	 */
	public function testText($d)
	{
		$this->assertInstanceOf("HtmlNode\\Dependency\\Text", $d->text());
		$this->assertInstanceOf(get_class($d), $d->text("bar"));
		
		// Test untextable
		try {
			Node::make("input")->text("foo");
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("LogicException", $e);
		}
	}
	/**
	 * @dataProvider getNodeTests
	 */
	public function testContains($d)
	{
		$this->assertTrue($d->contains("World"));
	}

  public function getNodeTests()
  {
    return [[Node::make("div", "Hello World 1")]];
  }

  public function getMacroTests()
  {
  	Node::macro("instance", function(){ return Node::make("input"); });
  	Node::macro("singleton", function(){ return Node::make("input"); }, true);
  	
  	$i1 = Node::instance();
  	$i2 = Node::instance();

  	$s1 = Node::singleton();
  	$s2 = Node::singleton();

    return [[$i1, $i2, $s1, $s2]];
  }

}

