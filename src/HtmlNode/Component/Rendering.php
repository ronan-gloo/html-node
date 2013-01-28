<?php

namespace HtmlNode\Component;

use HtmlNode\Compiler;

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