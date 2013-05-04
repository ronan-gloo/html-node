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
     * @var array
     */
    protected static $dependencies = null;

    /**
     * @param string $tag
     * @param string $text
     * @param array $attrs
     */
    public function __construct($tag = 'div', $text = '', array $attrs = [])
	{
		// link dependencies
        if (null === static::$dependencies)
        {
            static::$dependencies = [
                'text' 		=> new Dependency\Text,
                'attributes'=> new Collection\Attribute,
                'children' 	=> new Collection\Node
            ];
        }
		$this->text		    = clone static::$dependencies['text'];
		$this->attributes   = clone static::$dependencies['attributes'];
		$this->children     = clone static::$dependencies['children'];

        $this->text->node($this);

        $tag	and $this->tag($tag);
        $attrs 	and $this->attr($attrs);
        $text	and $this->text->set($text);
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
