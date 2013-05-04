<?php
/**
 * @Author      ronan.tessier@vaconsulting.lu
 * @Date        04/05/13
 * @File        AbstractNodeTest.php
 * @Copyright   Copyright (c) Bootstrap - All rights reserved
 * @Licence     Unauthorized copying of this source code, via any medium is strictly
 *              prohibited, proprietary and confidential.
 */

namespace HtmlNode\Tests;


use HtmlNode\Node;

class AbstractNodeTest extends \PHPUnit_Framework_TestCase {

    public function testConstructorCreatesDependencies()
    {
        $node = new Node();
        $node->__construct();
    }

}