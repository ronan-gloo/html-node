<?php

namespace HtmlNode\Component;

use
	HtmlNode\Util\Manager,
	HtmlNode\Collection\Node as Collection,
	HtmlNode\NodeInterface
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
			return Manager::compiler()
				->node($this)
				->children();
		}
		
		if ($data instanceof Collection)
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
			$child instanceof NodeInterface and $child->parent = $this;
		}
		
		$this->children->exchange($data);
		
		return $this;
	}

	/**
	 * Node html + text
	 * @access public
	 * @return void
	 */
	public function contents()
	{
		return Manager::compiler()
			->node($this)
			->contents();
	}

	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		return Manager::compiler()
			->node($this)
			->compile();
	}
	
	/**
	 * Only node, no text and html
	 * 
	 * @access public
	 * @return void
	 */
	public function self()
	{
		$compiler = Manager::compiler()->node($this);
		
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