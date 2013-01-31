<?php

namespace HtmlNode\Component;

use
	HtmlNode\Compiler,
	HtmlNode\Collection,
	HtmlNode\Node
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
			$data = $data->get();
		}
		// force the element to be 
		elseif (! is_array($data))
		{
			$data = [$data];
		}
		
		// set up the parent node
		foreach ($data as $child)
		{
			$child instanceof Node and $child->parent = $this;
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
		$compiler->text();
		$compiler->close();
		
		return $compiler->html();
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