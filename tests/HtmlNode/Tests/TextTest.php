<?php
namespace HtmlNode\Tests;

use HtmlNode\Dependency;
use HtmlNode\Node;

/**
 * Class TextTest
 * @package HtmlNode\Tests
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider getTextData
	 */
	public function testProperties($text)
	{
		$this->assertClassHasAttribute("text", get_class($text));
		$this->assertClassHasAttribute("position", get_class($text));
	}

    /**
     *
     */
    public function testConstructor()
    {
       $text = new Dependency\Text('hey');
       $this->assertSame("hey", $text->get());
    }

	/**
	 * @dataProvider getTextdata
	 */
	public function testToString($text)
	{
		$this->assertSame("foo", strval($text));
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testSet($text)
	{
		$this->assertSame("bar", strval($text->set("bar")));
		
		try {
			$text->set([]);
			$text->set(new stdClass);
		}
		catch (\Exception $e) {
			$this->assertInstanceOf("InvalidArgumentException", $e);
		}
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testPosition($text)
	{
		$this->assertEquals(0, $text->position());
		$this->assertEquals(1, $text->position(1)->position());
		$this->assertEquals(0, $text->position("string")->position());
	}

	/**
	 * @dataProvider getTextdata
	 */
	public function testLength($text)
	{
		$this->assertEquals(strlen($text->get()), $text->length());
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testContains($text)
	{
		$text->set("Hello World 1");
		
		$this->assertTrue($text->contains("World"));
		
		$this->assertFalse($text->contains("Foo"));
		// text case sensitive
		$this->assertFalse($text->contains("world", true));
		// test type ksensitive
		$this->assertFalse($text->contains(1, false, true));
	}

	/**
	 * @dataProvider getTextData
	 * @expectedException Exception
	 */
	public function testContainsEmpty($text)
	{
		$text->contains();
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testReplace($text)
	{
		$this->assertEquals("fee", $text->replace("o", "e"));
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testMatch($text)
	{
		$this->assertGreaterThan(0, $text->match("/[f]/"));
	}

	/**
	 * @dataProvider getTextData
	 */
	public function testGet($text)
	{
		$this->assertSame("foo", $text->get());
	}

    /**
     *
     */
    public function testFirst()
    {
        $node = new Node('div');
        $node->append('div', 'hey');

        $node->text()->position(5);
        $text2 = $node->text()->first();

        $this->assertEquals(0, $node->text()->position());
        $this->assertSame($text2, $node->text());
    }

    /**
     *
     */
    public function testLast()
    {
        $node = new Node('div', 'hey');
        $node->append('div', 'hey');
        $node->append('div', 'hey');
        $text2 = $node->text()->last();

        $this->assertEquals(2, $node->text()->position());
        $this->assertSame($text2, $node->text());
    }

    /**
     * @dataProvider getTextData
     */
    public function testBefore($text)
    {
        $node = new Node('div', 'hey');
        $node->append('div', 'hey');
        $last = (new Node())->appendTo($node);

        $this->assertEquals(1, $text->before($last)->position());
    }

    /**
     * @dataProvider getTextData
     */
    public function testAfter($text)
    {
        $node   = new Node('div', 'hey');
        $first  = (new Node())->appendTo($node);
        $node->append('div', 'hey');

        $this->assertEquals(1, $text->after($first)->position());
    }

    public function getTextData()
	{
		$text = new Dependency\Text("foo");
		
		return [[$text]];
	}
}

