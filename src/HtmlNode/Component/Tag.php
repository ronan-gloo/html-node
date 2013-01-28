<?php

namespace HtmlNode\Component;

use InvalidArguementException;

trait Tag {
	
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

}