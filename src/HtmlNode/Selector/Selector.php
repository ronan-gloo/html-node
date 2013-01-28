<?php

namespace HtmlNode\Selector;

use HtmlNode\Node;

class Selector {
	
	/**
	 * Boolean / named global attributes.
	 * Supports HTML5 Global attributes
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $attributes = [
		"checked",
		"required",
		"enabled",
		"disabled",
		"hidden",
		"draggable",
		"dropable",
		"contenteditable",
		"spellcheck",
		"translate"
	];
	
	/**
	 * @access public
	 * @static
	 * @param mixed $attrs
	 * @param mixed $string
	 * @return void
	 */
	public static function pseudo(Node $node, $str)
	{
		$substr = substr($str, 1);
		$attrs	= $node->attr();

		switch (substr($str, 0, 1))
		{
			case ".":
			$attr = ($key = array_search($substr, $attrs["class"])) !== false 
				? $attrs["class"][$key]
				: null;
			break;
			
			case "#":
			$attr = (isset($attrs["id"]) and $attrs["id"] === $substr) ? $attrs["id"] : null;
			break;
			
			case ":":
			$attr = (in_array($substr, static::$attributes) and isset($attrs[$substr]))
				? $attrs[$substr]
				: null;
			break;
			
			default:
			$attr = ($node->tag() === $str) ? $node->tag() : null;
			break;
		}
		return $attr;
	}
}