<?php

namespace HtmlNode\Util;

use
	Closure,
	HtmlNode\Collection,
	HtmlNode\Compiler,
	HtmlNode\Dependency
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
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $compiler = null;
	
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
	 * @param mixed $class: the clas name provided
	 * @param mixed $dependecies
	 * @return void or new Node instance
	 */
	public static function node($class)
	{
		if (! static::$dependencies)
		{
			static::$dependencies = [
				'text' 			=> new Dependency\Text,
				'attributes'=> new Collection\Attribute,
				'children' 	=> new Collection\Node
			];
		}
		foreach(static::$dependencies as $dep)
		{
			$deps[] = clone $dep;
		}
		return new $class($deps[0], $deps[1], $deps[2]);
	}
	
	/**
	 * Set / get the node compiler.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function compiler(CompilerInterface $compiler = null)
	{
		if ($compiler)
		{
			static::$compiler = $compiler;
		}
		if (! static::$compiler)
		{
			static::$compiler = new Compiler();
		}
		return clone static::$compiler;
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
		return isset(static::$registry[$name]);
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