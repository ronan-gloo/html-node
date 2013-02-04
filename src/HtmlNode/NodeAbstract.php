<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Component,
	OutOfBoundsException
;

abstract class NodeAbstract implements NodeInterface {
	
	use	Component\Attribute,
			Component\Css,
			Component\Config,
			Component\Manipulation,
			Component\Rendering,
			Component\Seek,
			Component\Tag,
			Component\Traversing;
	
	/**
	 * The text dependencie
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $text;
	
	/**
	 * Class Constructor.
	 * 
	 * @access public
	 * @param mixed $tag (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct(Dependency\Text $text, Collection\Attribute $attrs, Collection\Collection $children)
	{		
		// link dependencies
		$this->text				= $text;
		$this->attributes = $attrs;
		$this->children		= $children;
	}
	
	/**
	 * Catch property then check if its a dependency
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function __get($key)
	{
		if (isset($this->$key) and $this->$key instanceof Dependency\Node)
		{	
			return $this->$key;
		}
		throw new OutOfBoundsException(__CLASS__."::$$key property doesn t exists");
	}
	
}
