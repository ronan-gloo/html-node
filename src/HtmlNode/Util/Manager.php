<?php

namespace HtmlNode\Util;

use Closure;

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
	public static function resolve($name, $args)
	{
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
	
}