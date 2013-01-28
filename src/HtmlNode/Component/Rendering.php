<?php

namespace HtmlNode\Component;

use
	HtmlNode\Compiler,
	HtmlNode\Collection
;

trait Rendering {

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