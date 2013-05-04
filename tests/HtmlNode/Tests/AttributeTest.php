<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
	/** @dataProvider getAttributeTests */
	public function testProperties($d)
	{
		$this->assertClassHasAttribute("attributes", get_class($d));
	}
	
	/**
	 * @dataProvider getAttributeTests
	 */
	public function testAttr($d, $attrs)
	{
		$this->assertInstanceOf(get_class($d), $d->attr($attrs));
		$this->assertSame("email", $d->attr("name"));

        $d->attr('key', 'val');
        $this->assertSame('val', $d->attr('key'));

        $d->attr('style', ['display' => 'none']);
        $this->assertSame(['display' => 'none'], $d->attr('style'));
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testAddAttrIf($d)
	{
		// test nothing set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value2" => "v1"], false));
		$this->assertNull($d->attr("value1"));

		// test nothing set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value2" => "v2"], false, true));
		$this->assertNull($d->attr("value2"));

		// test is set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value3" => "v3"], true));
		$this->assertSame("v3", $d->attr("value3"));

		// test is set
		$this->assertInstanceOf(get_class($d), $d->addAttrIf(["value4" => "v4"], true, true));
		$this->assertSame("v4", $d->attr("value4"));
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testRemoveAttr($d, $attrs)
	{
		$this->assertInstanceOf(get_class($d), $d->removeAttr("foo"));
		$this->assertNull($d->attr($attrs)->removeAttr("name")->attr("name"));
	}

	/**
	 * @dataProvider getClassesTests
	 */
	public function testAddClass($d, $classes)
	{
		$this->assertInstanceOf(get_class($d), $d->addClass("foo"));
		
		$d->addClass($classes);
		
		if (is_string($classes)) $classes = explode(" ", $classes);
		
		foreach ($classes as $class)
		{
			$this->assertContains($class, $d->attr("class"));
		}
	}

    /**
     * @dataProvider getClassesTests
     */
    public function testAddClassIf($node)
    {
        // test class is added with 2 args
        $node->addClassIf('go', false);
        $this->assertNotContains('go', $node->attr('class'));

        $node->addClassIf('back', true);
        $this->assertContains('back', $node->attr('class'));

        // test class is added with 3 args
        $node->addClassIf('panda', 2, 1);
        $this->assertNotContains('panda', $node->attr('class'));

        $node->addClassIf('panda', 0, 0);
        $this->assertContains('panda', $node->attr('class'));
    }

    /**
     * @dataProvider getClassesTests
     */
    public function testRemoveClassIf($node)
    {
        $node->addClass(['go', 'back', 'panda']);

        // test class is added with 2 args
        $node->removeClassIf('go', false);
        $this->assertContains('go', $node->attr('class'));

        $node->removeClassIf('back', true);
        $this->assertNotContains('back', $node->attr('class'));

        // test class is added with 3 args
        $node->removeClassIf('panda', 2, 1);
        $this->assertContains('panda', $node->attr('class'));

        $node->removeClassIf('panda', 0, 0);
        $this->assertNotContains('panda', $node->attr('class'));
    }

	/**
	 * @dataProvider getClassesTests
	 */
	public function testHasClass($d, $classes)
	{
		$d->addClass($classes);
		
		if (is_string($classes)) $classes = explode(" ", $classes);
		
		foreach ($classes as $class)
		{
			$this->assertTrue($d->hasClass($class));
		}
	}

	/**
	 * @dataProvider getClassesTests
	 */
	public function testRemoveClass($d, $classes)
	{
		$this->assertInstanceOf(get_class($d), $d->removeClass("foo"));
		
		$d->addClass($classes);
		$d->removeClass($classes);
		$this->assertEmpty($d->attr("class"));
	}
	
	/**
	 * @dataProvider getClassesTests
	 */
	public function testSwitchClass($d)
	{
		$this->assertInstanceOf(get_class($d), $d->switchClass("foo", "bar"));
		
		// Note: use array value cause classes array keeps indexes
		
		// Test if 'bar' class has been added, even if 'foo' doesn't exists
		$this->assertSame(['bar'], array_values($d->attr("class")));
		// Test if 'bar' is removed, and 'foo' is added
		$this->assertSame(['foo'], array_values($d->switchClass('bar', 'foo')->attr('class')));
	}
	
	/**
	 * @dataProvider getDataTests
	 */
	public function testData($d, $key, $val, $array)
	{
		$this->assertInstanceOf(get_class($d), $d->data($key, $val));
		$this->assertInstanceOf(get_class($d), $d->data("foo", $array));
		
		$this->assertSame($val, $d->data($key));
		$this->assertSame($array, $d->data("foo"));
		
		$this->assertSame("foo3", $d->data("foo.foo2.bar2"));
		
		$this->assertContains($array, $d->data());
	}
    /**
	 * @dataProvider getDataTests
	 */
	public function testAria($d, $key, $val, $array)
	{
		$this->assertInstanceOf(get_class($d), $d->aria($key, $val));
		$this->assertInstanceOf(get_class($d), $d->aria("foo", $array));

		$this->assertSame($val, $d->aria($key));
		$this->assertSame($array, $d->aria("foo"));

		$this->assertSame("foo3", $d->aria("foo.foo2.bar2"));

		$this->assertContains($array, $d->aria());
	}

	/**
	 * @dataProvider getAttributeTests
	 */
	public function testIs($d, $attrs)
	{
		$d->attr($attrs);
		
		$this->assertTrue($d->is("[name]"));
		$this->assertTrue($d->is("[name=\"email\"]"));
		$this->assertTrue($d->is(".test"));
		$this->assertTrue($d->is("#foo"));
		$this->assertTrue($d->is(":enabled"));
		$this->assertTrue($d->is("div"));
		
		$this->assertFalse($d->is("[name=\"email"));
		$this->assertFalse($d->is("[foo]"));
		$this->assertFalse($d->is("[name=\"foo\"]"));
		$this->assertFalse($d->is(".bar"));
		$this->assertFalse($d->is("#bar"));
		$this->assertFalse($d->is(":bar"));
		$this->assertFalse($d->is("input"));
	}
	
	
  public function getAttributeTests()
  {
      /** @var \HtmlNode\Node $node */
      $node = Node::make("div");
    return [
    	[$node,
    	["class" => "test", "data" => "val", "name" => "email", "id" => "foo", "enabled" => "enabled"],
    ]];
  }
  public function getClassesTests()
  {
      /** @var \HtmlNode\Node $node */
      $node = Node::make("div");
    return [[$node, ["foo", "bar"]], [Node::make("div", "text"), "foo bar"]];
  }
  public function getDataTests()
  {
      /** @var \HtmlNode\Node $node */
      $node = Node::make("div");
    return [[$node, "foo1", "bar1", ["foo2" => ["bar2" => "foo3"]]]];
  }
}

