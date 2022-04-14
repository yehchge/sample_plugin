<?php
/**
 * Application class
 *
 * @author Shameer
 */
class Application {
   private $plugins = array();
   public function  __construct() {
       // Constructor
   }
   public function run()
   {
       //simply start a new instance of plugin to use in this example
       $newsLetter = new Newsletter();
       //Load in the plugin reflection classes
       $plugins = $this->bindPlugins();
       //Build the final menu
       $menu = $this->buildMenu();
       print_r($menu);
   }
   public function bindPlugins()
   {
       //get all instantiated classes
       $classes = get_declared_classes();
       //check which all classes implements the basic Plugin interface
       foreach($classes as $class) {
           $reflectClass = new ReflectionClass($class) ;
           if($reflectClass->implementsInterface('Plugin')) {
               //We are actually storing the instance reflection class itself
               //Not the class name or its object
               $this->plugins[] = $reflectClass;
           }
       }
       return $this->plugins;
   }
   public function buildMenu()
   {
       //Get the basic menus
       $menu = $this->getMainMenu();
       $result = $this->runEvent('adminMenu', $menu);
        if(is_array($result)) {
            $menu = array_merge($menu, $result);
        }
       return $menu;
   }
   function runEvent($method, $args) {
       $return = array();
       foreach($this->plugins as $plugin) {
           if($plugin->hasMethod($method)) {
               $reflectMethod = $plugin->getMethod($method);
               //If the method is static we dont need the class instance
               if($reflectMethod->isStatic()) {
                   $list = $reflectMethod->invoke($args);
               }
               else {
                   //Create a new instance of the class
                   $instance = $plugin->newInstance();
                   $list = $reflectMethod->invoke($instance,$args);
               }
               $return = array_merge($return, $list);
           }
       }
       return $return;
   }
   public function getMainMenu()
   {
       return array('Articles','News','Blog');
   }
}