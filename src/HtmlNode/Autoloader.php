<?php

namespace HtmlNode;

class Autoloader {
	
	public static function register()
  {
	  spl_autoload_register(array(new self, 'autoload'));
  }

  public static function autoload($class)
	{
    if (strpos($class, __NAMESPACE__) !== 0)
    {
	    return;
    }
    if (file_exists($file = __DIR__ . '/../' . strtr($class, '\\', '/').'.php'))
    {
	    require $file;
    }
	}
	
}