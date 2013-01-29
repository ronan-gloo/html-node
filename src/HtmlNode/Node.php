<?php

namespace HtmlNode;

use
	HtmlNode\Component,
	HtmlNode\Dependency,
	HtmlNode\Util,
	InvalidArgumentException,
	LogicException,
	OutOfBoundsException,
	BadMethodCallException
;

class Node implements NodeInterface {
	
	use	Component\Attribute,
			Component\Css,
			Component\Config,
			Component\Manipulation,
			Component\Rendering,
			Component\Seek,
			Component\Tag,
			Component\Traversing;
	
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
			if (($node = Util\Manager::resolve($m, $args)) instanceof Node)
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
	public static function make($tag = "div", $text = "", $attrs = [])
	{
		return new static($tag, $text, $attrs);
	}
	
	/**
	 * Class Constructor.
	 * 
	 * @access public
	 * @param mixed $tag (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct($tag = "div", $text = "", $attrs = [])
	{		
		// Init components
		$this->tag($tag);
		$this->attributes($attrs);
		$this->manipulation();
		
		// instanciate dependencies
		$this->text = new Dependency\Text($text);
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
	
	/**
	 * Set the node text.
	 * 
	 * @access public
	 * @param bool $text (default: false)
	 * @return void
	 */
	public function text($text = false)
	{
		if (func_num_args() === 0) return $this->text;
		
		if ($this->autoclose === true or $this->config["text"] === false)
		{
			throw new LogicException("Cannot add text on ".$this->tagname." element");
		}
		
		$this->text->position($this->children()->length() - 1);
		$this->text->set($text);
	
		return $this;
	}
	
	/**
	 * An alias to Dependency\Text::contains().
	 */
	public function contains($str, $case = false, $strict = false)
	{
		return $this->text->contains($str, $case, $strict);
	}	
}