<?php

namespace HtmlNode;

use
	HtmlNode\Component,
	HtmlNode\Dependency,
	HtmlNode\Util,
	LogicException,
	InvalidArgumentException
;

class Node implements NodeInterface {
	
	use Component\Css,
			Component\Attribute,
			Component\Manipulation,
			Component\Traversing,
			Component\Config,
			Component\Seek {
				Component\Attribute::build as protected attributes;
				Component\Manipulation::init as protected manipulation;
			}

	/**
	 * Tagname string.
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $tagname;
	
	/**
	 * Element autclosed or not
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $autoclose;
	
	/**
	 * Autoclosed tags.
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $autoclosed = [
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	];

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
	 * @access public
	 * @static
	 */
	public static function all()
	{
		return Util\Master::all();
	}
	
	/**
	 * @access public
	 * @static
	 * @param mixed $c
	 */
	public static function each($c)
	{
		return Util\Master::each($c);
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
	public static function make($tag = "", $attrs = [], $text = "")
	{
		return new static($tag, $attrs, $text);
	}
	
	/**
	 * Class Constructor.
	 * 
	 * @access public
	 * @param mixed $tag (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct($tag = "", $attrs = [], $text = "")
	{
		if (! is_array($attrs))
		{
			$temp		= $attrs;
			$attrs	= is_array($text) ? $text : [];
			$text		= $temp;
		}
		
		// Init components
		$this->tag($tag);
		$this->attributes($attrs);
		$this->manipulation();
		
		// instanciate dependencies
		$this->text = new Dependency\Text($text);
		
		// register node to the manager
		Util\Master::register($this);
	}
	
	/**
	 * Set / Get tagname.
	 * 
	 * @access public
	 * @param string $tag (default: "")
	 * @return void
	 */
	public function tag($tagname = "")
	{
		if (func_num_args() === 0) return $this->tagname;
		
		// Only strings are accepted
		if (! is_string($tagname))
		{
			throw new InvalidArgumentException("Tagname must be a string");
		}
		
		// Set the tagname: be sure there is no html elements
		$this->tagname = strip_tags(trim($tagname));
		
		// Define if Node should be autoclosed or not
		$this->autoclose = in_array($this->tagname, static::$autoclosed);
	
		return $this;
	}
	
	/**
	 * Ask for autoclosed tag.
	 * 
	 * @access public
	 * @return void
	 */
	public function autoclose()
	{
		return $this->autoclose;
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
		
		if ($this->autoclose === true or ! $this->config("text"))
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
	
	/**
	 * 
	 * @access public
	 * @return void
	 */
	public function html($data)
	{
		if (! $data)
		{
			return (new Compiler($this))->children();
		}
		
		$this->children->replaceWith((array)$data);
		
		return $this;
	}

	/**
	 * 
	 * @access public
	 * @return void
	 */
	public function contents()
	{
		return (new Compiler($this))->contents();
	}

	/**
	 * @access public
	 * @return void
	 */
	public function render($childs = true)
	{
		return (new Compiler($this))->node();
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function __toString()
	{
		return $this->render();
	}
	
}