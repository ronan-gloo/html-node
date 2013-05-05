<?php
/**
 * @Author      ronan.tessier@vaconsulting.lu
 * @Date        04/05/13
 * @File        CompilerTest.php
 * @Copyright   Copyright (c) Bootstrap - All rights reserved
 * @Licence     Unauthorized copying of this source code, via any medium is strictly
 *              prohibited, proprietary and confidential.
 */

namespace HtmlNode\Tests;

use  \HtmlNode\Compiler;
use  \HtmlNode\Node;

class CompilerTest extends \PHPUnit_Framework_TestCase {

    /** @var \HtmlNode\Compiler */
    protected $compiler;
    protected $node;

    public function setUp()
    {
        $this->node = new Node('div');
        $this->compiler = new Compiler($this->node);
    }

    public function testDataArray()
    {
        $this->assertEquals(
            '<div data-doo="da">',
            $this->compiler->data(['doo' => 'da'])->open()->html()
        );
    }

    public function testStyles()
    {
        $this->assertEquals(
            '<div style="display:none;">',
            $this->compiler->styles(['display' => 'none'])->open()->html()
        );
    }

    public function testDataMultiArray()
    {
        $this->assertEquals(
            '<div data-doo-da="da">',
            $this->compiler->data(['doo' => ['da' => 'da']])->open()->html()
        );
    }

    public function testAttrOpen()
    {
        $this->node->attr('bla', 'do');
        $this->assertEquals('<div bla="do">', $this->compiler->open()->html());
    }

    public function testChildrenWithText()
    {
        (new Node('div'))->appendTo($this->node);
        $this->node->text('test');
        $this->assertEquals('<div></div>test', $this->compiler->children()->html());
    }

    public function testChildrenMoveText()
    {
        (new Node('div'))->appendTo($this->node);
        $this->node->text('test');
        $this->node->text->first();
        $this->assertEquals('test<div></div>', $this->compiler->children()->html());
    }

    public function testChildrenNoText()
    {
        (new Node('div'))->appendTo($this->node);
        $this->node->text('test');
        $this->assertEquals('<div></div>', $this->compiler->children(false)->html());
    }

}