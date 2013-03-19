<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Dependency,
	OutOfBoundsException,
	LogicException,
	BadMethodCallException
;

class Node extends NodeAbstract {
	
	/**
	 * Text instance
	 * @var mixed
	 * @access protected
	 */
	protected $text;

	/**
	 * ASk to the manager if $m is registered,
	 * then returns it
	 * 
	 * @access public
	 * @static
	 * @param mixed $m
	 * @param mixed $args
	 * @return void
	 */
	public static function __callStatic($m, $args)
	{
		if (Util\Manager::registered($m))
		{
			// if the resolver returns an instance of Node, we are done.
			if (($node = Util\Manager::resolve($m, $args)) instanceof NodeInterface)
			{
				return $node;
			}
		}
		// Nothing to do more...
		throw new BadMethodCallException("Method $m does not exists");
	}
	
	/**
	 * @access public
	 * @static
	 * @param mixed $name
	 * @param mixed $data
	 */
	public static function macro($name, $data, $once = false)
	{
		return Util\Manager::register($name, $data, $once);
	}
	
	/**
	 * 
	 * @access public
	 * @static
	 * @param mixed $tag (default: null)
	 * @param mixed $contents (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function make($tag = null, $text = null, $attrs = [])
	{
		$node = Util\Manager::node(get_called_class());
		
		$tag		and $node->tag($tag);
		$attrs 	and $node->attr($attrs);
		$text		and $node->text($text);
		
		return $node;
	}
}