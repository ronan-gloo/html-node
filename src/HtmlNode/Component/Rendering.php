<?php

namespace HtmlNode\Component;

use
	HtmlNode\Util\Manager,
	HtmlNode\Collection\Node as Collection,
	HtmlNode\NodeInterface
;

/**
 * Class Rendering
 * @package HtmlNode\Component
 */
trait Rendering {

    /**
     * @param null $data
     * @return $this
     */
    public function html($data = null)
	{
		if (is_null($data))
		{
			return Manager::compiler()
				->node($this)
				->children()
                ->html();
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
     * @return mixed
     */
    public function contents()
	{
		return Manager::compiler()
			->node($this)
			->contents()
            ->html();
	}

    /**
     * @return string
     */
    public function render()
	{
		return Manager::compiler()
			->node($this)
			->compile();
	}

    /**
     * @return mixed
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
     * @return string
     */
    public function __toString()
	{
		return $this->render();
	}
	
}