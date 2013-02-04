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
	public static function make($tag = "div", $text = null, $attrs = [])
	{
		$node = Util\Manager::node(get_called_class());
		$node->tag($tag);
		
		if ($attrs) $node->attr($attrs);
		if ($text) $node->text($text);
		
		return $node;
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
		$this->text->position($this->children()->length());
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