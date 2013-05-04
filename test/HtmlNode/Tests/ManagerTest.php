<?php

namespace HtmlNode\Tests;

use HtmlNode\Collection\Collection;
use HtmlNode\Compiler;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getClass
	 */
	public function testAttributes($class)
	{
		$this->assertClassHasStaticAttribute("registry", $class);
		$this->assertClassHasStaticAttribute("singletons", $class);
	}

	/**
	 * @dataProvider getClass
	 */
	public function testRegister($class)
	{
		$class::register("input", "test");
		$this->assertTrue($class::registered("input"));
		
		$class::register("input2", "test", true);
		$this->assertTrue($class::registered("input2"));
	}

    /**
     * @dataProvider getClass
     */
    public function testAddCompiler($class)
    {
        $compiler = new Compiler();
        $class::compiler($compiler);

        $this->assertEquals($compiler, $class::compiler());
    }

    /**
     * @dataProvider getClass
     */
    public function compilerReturnsClone($class)
    {
        $compiler = new Compiler();
        $class::compiler($compiler);

        $this->assertNotSame($compiler, $class::compiler());
    }
	
	/**
	 * @dataProvider getClass
	 */
	public function testOnce($class)
	{
		$class::once("input", "test");
		$this->assertTrue($class::registered("input"));
	}
	
	/**
	 * @dataProvider getClass
	 */
	public function testResolveEmpty($class)
	{
		$this->assertFalse($class::resolve("foo", []));
	}

	/**
	 * @dataProvider getClass
	 */
	public function testResolveObject($class)
	{
		$class::register("odd", new \stdClass);
		$this->assertInstanceOf("stdClass", $class::resolve("odd", []));
	}

	/**
	 * @dataProvider getClass
	 */
	public function testResolveInstance($class)
	{
		$class::register("bar", function(){
			return new \stdClass;
		});
		$std = $class::resolve("bar", []);
		
		// Test singleton
		$this->assertNotSame($std, $class::resolve("bar", []));
	}
	
	/**
	 * @dataProvider getClass
	 */
	public function testResolveSingleton($class)
	{		
		$class::once("input", function(){
			return new \stdClass;
		});
		
		$std = $class::resolve("input", []);
		
		// Returns the input
		$this->assertInstanceOf("stdClass", $std);
		// Test singleton
		$this->assertSame($std, $class::resolve("input", []));
	}

	public function getClass()
	{
		return [['HtmlNode\Util\Manager']];
	}
}