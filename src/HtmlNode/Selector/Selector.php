<?php

namespace HtmlNode\Selector;

use HtmlNode\Node;

class Selector {
	
	const REGEXP_FULL_ATTR		= '/\[(\w+)="(\w+)"\]$/';
	
	const REGEXP_SIMPLE_ATTR	= '/\[(\w+)]$/';
	
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
		$str		= stripslashes($str);
		
		switch (substr($str, 0, 1))
		{
			case ".":
			$attr = ($key = array_search($substr, $attrs["class"])) !== false;
			break;
			
			case "#":
			$attr = (isset($attrs["id"]) and $attrs["id"] === $substr);
			break;
			
			case ":":
			$attr = (in_array($substr, static::$attributes) and isset($attrs[$substr]));
			break;
			
			case "[":
			// try name + val
			if (preg_match(self::REGEXP_FULL_ATTR, $str, $match))
				$attr = ($element = $node->attr($match[1])) === $match[2];
			// Just name
			elseif (preg_match(self::REGEXP_SIMPLE_ATTR, $str, $match))
				$attr = $node->attr($match[1]) !== null;
			else
				$attr = false;
			break;
			
			default:
			$attr = $node->tag() === $str;
			break;
		}
		return $attr;
	}
}