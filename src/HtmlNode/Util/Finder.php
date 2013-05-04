<?php

namespace HtmlNode\Util;

use
	HtmlNode\Collection\Node as Collection,
	HtmlNode\NodeInterface
;

/**
 * TODO: supports selectors ...etc
 */
class Finder {

    /**
     * @var \HtmlNode\NodeInterface
     */
    protected $node;
    /**
     * @var
     */
    protected $results;

    /**
     * @param NodeInterface $node
     */
    public function __construct(NodeInterface $node)
	{
		$this->node		= $node;
		$this->result	= new Collection;
	}

    /**
     * @param $input
     * @param bool $loop
     * @return Collection
     */
    public function children($input, $loop = true)
	{
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
     * @param $input
     * @param bool $loop
     * @return Collection
     */
    public function parents($input, $loop = true)
	{
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
     * @return Collection
     */
    public function result()
	{
		return $this->result;
	}
}