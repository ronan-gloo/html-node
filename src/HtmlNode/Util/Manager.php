<?php

namespace HtmlNode\Util;

use
	Closure,
	HtmlNode\Collection,
	HtmlNode\Compiler,
	HtmlNode\CompilerInterface,
	HtmlNode\Dependency
;

/**
 * Manage Nodes templates.
 */
class Manager {
	
	/**
	 * Node Templates registry
	 * (default value: [])
	 * @var mixed
	 * @access protected
	 */
	protected static $registry = [];
	
	/**
	 * Singleton elements
	 * (default value: [])
	 * @var mixed
	 */
	protected static $singletons = [];
	
	/**
	 * (default value: null)
	 * @var mixed
	 */
	protected static $compiler = null;

    /**
     * Register a new element
     * @param $name
     * @param $resolver
     * @param bool $singleton
     */
    public static function register($name, $resolver, $singleton = false)
	{
		static::$registry[$name] = compact("singleton", "resolver");
	}

    /**
     * Register a singleton: the callback will only be caled
     * once, then stored for further calls
     * @param $name
     * @param $resolver
     */
    public static function once($name, $resolver)
	{
		static::register($name, $resolver, true);
	}

    /**
     * Set / get the node compiler
     * @param CompilerInterface $compiler
     * @return mixed|null
     */
    public static function compiler(CompilerInterface $compiler = null)
	{
		if ($compiler)
		{
			static::$compiler = $compiler;
		}
		if (! static::$compiler)
		{
			static::$compiler = new Compiler;
		}
		return clone static::$compiler;
	}

	/**
	 * Check if a callback has been registered into the container.
	 * 
	 * @access public
	 * @static
	 * @param mixed $name
	 * @return boolean
	 */
	public static function registered($name)
	{
		return isset(static::$registry[$name]);
	}

    /**
     * Get a registered Node, then run the callback
     * @param $name
     * @param array $args
     * @return bool|mixed
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