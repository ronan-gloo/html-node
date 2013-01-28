<?php

namespace HtmlNode\Dependency;

/**
 * Implements the basics node dependencies.
 */
interface Node {
	
	public function set($data);
	
	public function get();
	
	public function __toString();
}