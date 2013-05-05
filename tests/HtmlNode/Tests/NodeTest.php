<?php
namespace HtmlNode\Tests;

use HtmlNode\Node;

/**
 * Class NodeTest
 * @package HtmlNode\Tests
 */
class NodeTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testProperties()
	{
		$this->assertClassHasAttribute("text", "HtmlNode\\Node");
	}

    /**
     *
     */
    public function testInstance()
	{
		$this->assertInstanceOf("HtmlNode\\Node", Node::make());
	}

    /**
     *
     */
    public function testConstructor()
	{
		$node = Node::make("div", "foo", ["class" => "test"]);
		
		$this->assertEquals($node->tag(), "div");
		$this->assertEquals($node->text(), "foo");
		$this->assertEquals($node->attr("class"), "test");
	}
	
	/**
	 * @dataProvider getNodeTests
	 */
	public function testTag($d)
	{
		$this->assertSame("div", $d->tag());
	}
	/**
     * @expectedException \BadMethodCallException
     */
	public function testUnregisteredMacro()
	{
	    Node::foo();
	}

    /**
     * @expectedException \InvalidArgumentException
     */
	public function testInvalidMacro()
	{
        Node::macro('test', []);
	    Node::test();
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
        $this->assertNull(Node::macro("foo", function(){
            return Node::make("input");
        }));
    }

	/**
	 * @dataProvider getNodeTests
	 */
	public function testDependencies($d)
	{
		$this->assertInstanceOf('HtmlNode\Dependency\DependencyInterface', $d->text);
		
		try {
			$d->foo;
		}
		catch (\Exception $e) {
			$this->assertInstanceOf('OutOfBoundsException', $e);
		}
	}
	
	/**
	 * @dataProvider getNodeTests
	 */
	public function testText($d)
	{
		$this->assertInstanceOf("HtmlNode\\Dependency\\Text", $d->text());
		$this->assertInstanceOf(get_class($d), $d->text("bar"));

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

    /**
     * @return array
     */
    public function getNodeTests()
  {
    return [[Node::make("div", "Hello World 1")]];
  }

    /**
     * @return array
     */
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

