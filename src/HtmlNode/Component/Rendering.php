<?php

namespace HtmlNode\Component;

use
	HtmlNode\Compiler,
	HtmlNode\Collection
;

trait Rendering {

	/**
	 * Node children
	 * @access public
	 * @return void
	 */
	public function html($data = null)
	{
		if (is_null($data))
		{
			return (new Compiler($this))->children();
		}
		if ($data instanceof Collection\Collection)
		{
			$this->children = $data;
		}
		// force the element to be 
		elseif (! is_array($data))
		{
			$data = [$data];
		}
		
		$this->children->replaceWith($data);
		
		return $this;
	}

	/**
	 * Node html + text
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
	public function render()
	{
		return (new Compiler($this))->node();
	}
	
	/**
	 * Only node, no text and html
	 * 
	 * @access public
	 * @return void
	 */
	public function self()
	{
		$compiler = new Compiler($this);
		$compiler->open();
		
		return $compiler->close();
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