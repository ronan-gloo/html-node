<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Component,
	OutOfBoundsException
;
use HtmlNode\Dependency\DependencyInterface;

/**
 * Class NodeAbstract
 * @package HtmlNode
 */
abstract class NodeAbstract implements NodeInterface {
	
	use	Component\Attribute,
			Component\Css,
			Component\Manipulation,
			Component\Rendering,
			Component\Seek,
			Component\Tag,
			Component\Text,
			Component\Traversing;

    /**
     * @param string $tag
     * @param string $text
     * @param array $attrs
     */
    public function __construct($tag = 'div', $text = '', array $attrs = [])
	{
		$this->text = (new Dependency\Text($text))->node($this);
		$this->attributes = new Collection\Attribute($attrs);
		$this->children = new Collection\Node;

        $this->tag($tag);
	}

    /**
     * @param $key
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function __get($key)
	{
		if (isset($this->$key) and $this->$key instanceof DependencyInterface)
		{	
			return $this->$key;
		}
		throw new OutOfBoundsException(__CLASS__."::$$key property doesn t exists");
	}
	
}
