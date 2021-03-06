<?php

namespace HtmlNode\Tests;

use HtmlNode\Node;

class ManipulationTest extends \PHPUnit_Framework_TestCase
{
		/** @dataProvider getManipulationTests */
    public function testAttributes($node)
    {
    	$this->assertClassHasAttribute('children', get_class($node));
    	$this->assertClassHasAttribute('parent', get_class($node));
    }
    
    /** @dataProvider getManipulationTests */
    public function testWrapNewInstance($d1, $d2)
    {
	    $this->assertInstanceOf(get_class($d1), $d1->wrap($d2));
    	$this->assertInstanceOf(get_class($d1), $d1->parent());
	    $this->assertInstanceOf(get_class($d1), $d1->wrap("input"));
    	$this->assertNotEquals($d1->wrap($d2), $d2->children()->first());
    }

    /** @dataProvider getManipulationTests */
    public function testUnwrap($d1, $d2)
    {
    	$this->assertNull($d1->wrap($d2)->unwrap()->parent());
    }

    /** @dataProvider getManipulationTests */
    public function testDetach($d1, $d2)
    {
    	$this->assertNull($d1->wrap($d2)->detach()->parent());
    }

    /**
     * @dataProvider getManipulationTests
     * @expectedException \LogicException
     */
    public function testCreateNodeOnAutoClose($d1)
    {
        $d1->tag('input')->append('div');
    }

    /**
     * @dataProvider getManipulationTests
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidNode($d1)
    {
        $d1->append([]);
    }

    /**
     * @dataProvider getManipulationTests
     */
    public function testCreateNodeWithClosure($d1)
    {
        $d1->append(function() {
            return 'div';
        });
        $this->assertNotEmpty($d1->find('div'));
    }
    
     /** @dataProvider getManipulationTests */
    public function testAppend($d1, $d2, $d3, $d4)
    {
	    $this->assertSame($d1->append($d2), $d1);
	    $this->assertNotSame($d3->append($d4)->children()->first(), $d4);
    }
    
    /** @dataProvider getManipulationTests */
    public function testAppendTo($d1, $d2)
    {
    	$d5 = $d2->appendTo($d1);
	    $this->assertNotSame($d5, $d2);
	    $this->assertSame($d5, $d1->children()->first());
    }

    /** @dataProvider getManipulationTests */
    public function testPrepend($d1, $d2, $d3, $d4)
    {
        $d1->text('heu');

        $this->assertEquals(0, $d1->text->position());
	    $this->assertSame($d1->prepend($d2), $d1);
        $this->assertEquals(1, $d1->text->position());
	    $this->assertNotSame($d3->prepend($d4)->children()->first(), $d4);
    }

    /** @dataProvider getManipulationTests */
    public function testPrependTo($d1, $d2, $d3)
    {
    	// copy
        $d3 = $d3->appendTo($d1);
    	$d5 = $d2->prependTo($d1);

	    $this->assertEquals($d5->index(), $d3->index() - 1);
	    
	    $d1->text("ho");
	    $d6 = $d3->prependTo($d1);
	    $this->assertEquals($d1->text()->position(), $d6->index());
    }

    /** @dataProvider getManipulationTests */
    public function testInsertBefore($d1, $d2, $d3)
    {
    	$second = $d2->appendTo($d1);
    	$d3->insertBefore($second);
    	$d1->text("ho");
    	$third 	= $d3->insertBefore($second);
    	
    	// Index
    	$this->assertEquals(
    		$third->index(),
    		$second->index() - 1,
            'index'
    	);
    	// Text position
    	$this->assertEquals(
    	 	$d1->text()->position(),
    	 	$second->index() + 1,
            'text'
    	 );
    }

    /** @dataProvider getManipulationTests */
    public function testInsertAfter($d1, $d2, $d3)
    {
    	$second = $d2->appendTo($d1);
    	$d3->insertAfter($second);
    	$d1->text("ho");
    	$third 	= $d3->insertAfter($second);
    	
    	// Index
    	$this->assertEquals(
    		$third->index(),
    		$second->index() + 1
    	);
    	// Text position
    	$this->assertEquals(
    	 	$d1->text()->position(),
    	 	$third->index() + 1
    	 );
    }

    /** @dataProvider getManipulationTests */
    public function testReplaceWith($d1, $d2, $d3)
    {
    	$orig = $d2->appendTo($d1);
    	$orig->replaceWith($d3);

    	$this->assertNull($orig->parent());
    	$this->assertNotNull($d3->parent());
    	$this->assertContains($d3, $d1->children());
    }

    /** @dataProvider getManipulationTests */
    public function testClone($d1, $d2)
    {
    	$orig = $d1->appendTo($d2)->append($d2);
    	$clone = clone $orig;

    	$this->assertNull($clone->parent());
    	$this->assertNotSame($orig->children(), $clone->children());
    	$this->assertNotSame($orig->text(), $clone->text());
    	$this->assertSame($orig->attr(), $clone->attr());
    }

    public function getManipulationTests()
    {
	    return [array_fill(0, 4, Node::make("div"))];
    }
}

