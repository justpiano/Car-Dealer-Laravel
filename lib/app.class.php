<?php
  /**
   * Class App
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2023
   * @version $Id: app.class.php, v1.00 2023-01-20 18:20:24 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  final class App
  {
	  
      private static array $instances = array();
    
      /**
       * App::__callStatic()
       *
       * @param mixed $name
       * @param mixed $args
       * @return mixed|object|void|null
       */
      public static function __callStatic($name, $args)
      {
          try {
              if (!class_exists($name)) {
                  throw new Exception("Class name " . $name . " does not exists.");
              }
			  //make a new instance
              if (!in_array($name, array_keys(self::$instances))) {
                  //check for arguments
                  if (empty($args)) {
                      //new keyword will accept a string in a variable
                      $instance = new $name();
                  } else {
                      //we need reflection to instantiate with an arbitrary number of args
                      $rc = new ReflectionClass($name);
                      $instance = $rc->newInstanceArgs($args);
                  }
                  self::$instances[$name] = $instance;
              } else {
                  //already have one
                  $instance = self::$instances[$name];
              }
              return $instance;
          }
          catch (exception $e) {
			  Debug::AddMessage("warnings", '<i>Warning</i>', $e->getMessage());
          }
      }
  }