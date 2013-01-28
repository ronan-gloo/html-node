<?php

namespace HtmlNode\Util;

use
	Closure,
	InvalidArgumentException,
	HtmlNode\Collection,
	HtmlNode\	Node,
	HtmlNode\Query
;

/*
 * Represents the class Node, and global operations
*/
class Master {
	
	/**
	 * All instanciate nodes
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $nodes = null;
	
	/**
	 * Register instanciated nodes.
	 * 
	 * @access public
	 * @static
	 * @param Node $node
	 * @return void
	 */
	public static function register(Node $node)
	{
		if (is_null(self::$nodes))
		{
			self::$nodes = new Collection\Collection;
		}
		if (self::$nodes->has($node))
		{
			throw new InvalidArgumentException("The node is already register");
		}
		self::$nodes->append($node);
	}
	
	/**
	 * Get all registered nodes.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function all()
	{
		return self::$nodes;
	}
	
	/**
	 * Loop throught instaciated nodes.
	 * 
	 * @access public
	 * @static
	 * @param Closure $c
	 * @return void
	 */
	public static function each(Closure $c)
	{
		foreach (self::$nodes as $key => $node)
		{
			$c($node, $key);
		}
	}
	
	/**
	 * Render all or a an array of $nodes
	 * 
	 * @access public
	 * @static
	 * @param mixed $nodes (default: null): a instance of Node or an array of Node
	 * @return String
	 */
	public static function render($nodes = null)
	{
		func_num_args() === 0 and $nodes = self::$nodes;
		
		$nodes and $nodes instanceof Node and $nodes = [$nodes];
		
		$html = "";
	
		foreach ($nodes as $node)
		{
			// prevent no Node instance rendering
			if (! $node instanceof Node)
			{
				throw new InvalidArgumentException("Only instances of Node can be provided");
			}
			// Do not render childs here, $node will care
			if (! $node->parent())
			{
				$html .= $node->render();
			}
		}
		return $html;
	}
	
}