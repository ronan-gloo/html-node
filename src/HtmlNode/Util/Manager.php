<?php

namespace HtmlNode\Util;

use
	Closure,
	HtmlNode\Node,
	HtmlNode\Dependency,
	HtmlNode\Collection
;

/**
 * Manage Nodes templates.
 */
class Manager {
	
	/**
	 * Node Templates registry
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $registry = [];
	
	/**
	 * Singleton elements
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $singletons = [];
	
	/**
	 * Node prototypes
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $nodes = [];
	
	/**
	 * Node dependencies
	 * 
	 * (default value: [])
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $dependencies = [];
	
	/**
	 * Register a new element.
	 * 
	 * @access public
	 * @static
	 * @param mixed $name
	 * @param Closure $c
	 * @return void
	 */
	public static function register($name, $resolver, $singleton = false)
	{
		static::$registry[$name] = compact("singleton", "resolver");
	}
	
	/**
	 * Register a singleton: the callback will only be caled
	 * once, then stored for further calls
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function once($name, $resolver)
	{
		static::register($name, $resolver, true);
	}
	
	/**
	 * Inject prototyped dependencies and create a new node,
	 * or register a prototype if $dependecies are provided
	 * 
	 * @access public
	 * @static
	 * @param mixed $name
	 * @param mixed $dependecies
	 * @return void or new Node instance
	 */
	public static function node($name)
	{
		if (! static::$dependencies)
		{
			static::$dependencies = [
				'text' 			=> new Dependency\Text,
				'attributes'=> new Collection\Attribute(),
				'children' 	=> new Collection\Collection
			];
		}
		foreach(static::$dependencies as $key => $dep)
		{
			$deps[$key] = clone $dep;
		}
		return new $name($deps['text'], $deps['attributes'], $deps['children']);
	}

	/**
	 * Check if a callback has been registered into the container.
	 * 
	 * @access public
	 * @static
	 * @param mixed $name
	 * @return void
	 */
	public static function registered($name)
	{
		return array_key_exists($name, static::$registry);
	}
	
	/**
	 * Get a registered Node, then run the callback.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function resolve($name, $args = [])
	{
		// Look at the registry first
		if (isset(static::$nodes[$name]))
		{
			return static::node($name);
		}

		// Look at the registry first
		if (! isset(static::$registry[$name]))
		{
			return false;
		}

		// Singleton is registered, returns it
		if (isset(static::$singletons[$name]))
		{
			return static::$singletons[$name];
		}
				
		// Get the result: returns Node or callback
		if (static::$registry[$name]["resolver"] instanceof Closure)
		{
			$result = call_user_func_array(static::$registry[$name]["resolver"], $args);
		}
		else
		{
			$result = static::$registry[$name]["resolver"];
		}
		
		// Register the singleton for further calls
		if (static::$registry[$name]["singleton"] === true)
		{
			static::$singletons[$name] = $result;
		}
		
		return $result;
	}
			
}