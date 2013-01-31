<?php

namespace HtmlNode\Util;

use
	HtmlNode\Collection,
	HtmlNode\Node
;

/**
 * TODO: supports selectors ...etc
 */
class Finder {
	
	protected $node;
	protected $results;
	
	/**
	 * Set up the node to visits.
	 * 
	 * @access public
	 * @param Node $node
	 * @return void
	 */
	public function __construct(Node $node)
	{
		$this->node		= $node;
		$this->result	= new Collection\Collection;
	}

	/**
	 * @access public
	 * @return void
	 */
	public function children($input, $loop = true)
	{
		$this->result = new Collection\Collection;
		
		$iterator = function($nodes) use(&$iterator, $input, $loop)
		{
			foreach ($nodes as $node)
			{
				if ($node->is($input) === true)
				{
					$this->result->append($node);					
				}
				if ($loop === false) return $loop;

				if ($childs = $node->children())
				{
					$iterator($childs);
				}
			}
		};
		
		$iterator($this->node->children());
		
		return $this->result;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function parents($input, $loop = true)
	{
		$this->result = new Collection\Collection;
		
		if ($node = $this->node->parent())
		{
			$iterator = function($node) use(&$iterator, $input, $loop)
			{
				if ($node->is($input) === true)
				{
					$this->result->append($node);					
				}
				
				if ($loop === false) return $loop;

				if ($parent = $node->parent())
				{
					$iterator($parent);
				}
			};
			$iterator($node);
		}
		return $this->result;
	}
	
	/**
	 * 
	 * @access public
	 * @return void
	 */
	public function result()
	{
		return $this->result;
	}
}