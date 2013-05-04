<?php

namespace HtmlNode;

use
	HtmlNode\Collection,
	HtmlNode\Dependency,
	BadMethodCallException,
    InvalidArgumentException
;

/**
 * Class Node
 * @package HtmlNode
 */
class Node extends NodeAbstract {
	
	/**
	 * Text instance
	 * @var mixed
	 * @access protected
	 */
	protected $text;

    /**
     * @param $m
     * @param $args
     * @return bool|mixed
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public static function __callStatic($m, $args)
	{
		if (Util\Manager::registered($m))
		{
			// if the resolver returns an instance of Node, we are done.
			if (($node = Util\Manager::resolve($m, $args)) instanceof NodeInterface)
			{
				return $node;
			}
            throw new InvalidArgumentException("$m is not a valid node");
		}
		// Nothing to do more...
		throw new BadMethodCallException("Method $m does not exists");
	}

    /**
     * @param $name
     * @param $data
     * @param bool $once
     */
    public static function macro($name, $data, $once = false)
	{
		Util\Manager::register($name, $data, $once);
	}

    /**
     * @return object
     */
    public static function make()
	{
        return (new \ReflectionClass(get_called_class()))
            ->newInstanceArgs(func_get_args());
	}
}