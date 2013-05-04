<?php

namespace HtmlNode;

class Autoloader {
	
	public static function register()
  {
	  spl_autoload_register(array(new self, 'autoload'));
  }

  static public function autoload($class)
  {
    if (strncmp($class, 'HtmlNode', 8) !== 0) {
        return;
    }

    if (file_exists($file = __DIR__ . '/src/' . strtr($class, '\\', '/').'.php')) {
        require $file;
    }
  }	
}