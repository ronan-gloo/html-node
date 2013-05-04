<?php

namespace HtmlNode\Dependency;

use HtmlNode\NodeInterface;

/**
 * Implements the basics node dependencies.
 */
interface DependencyInterface {
	
	public function set($data);
	
	public function get();
	
	public function __toString();

    public function node(NodeInterface $node = null);
}