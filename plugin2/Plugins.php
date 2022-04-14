<?php
 
// function __autoload($class) {
//   include(getcwd() . "/plugins/" . ucfirst($class) . ".php");
// }

function my_autoloader($class){
  include(getcwd() . "/plugins/" . ucfirst($class) . ".php");
}

spl_autoload_register('my_autoloader');

 
 /*
    Static class responsible for every plugin
 */
 class Plugins {
 
  private static $plugins = array();
  private static $hooks = array();
  private static $plugin_count = 0;
  private static $hook_count = 0;
 
  public function __construct() {
   $dir = opendir(getcwd() . '/plugins');
   while (false !== ($filename = readdir($dir))) {
        if (stripos($filename, '.php') === false || strcmp($filename, 'IPlugin.php') == 0)
         continue;
 
        $name = str_replace('.php', '', $filename);
        array_push(self::$plugins, array(
          'name' => $name,
          'filename' => $filename,
          'filepath' => getcwd().'/plugins/'.$filename
         )
        );
    self::$plugin_count++;
        self::add_hook($name, true);
   }
  }
 
  public static function add_hook($name, $activate) {
   $hooks = self::$hooks;
   $length = count($hooks);
   for ($i = 0; $i < $length; $i++)
    if (in_array($name, $hooks[$i]) == true) {
     if ($hooks[$i]['name'] !== $name)
      continue;
     self::$hooks[$i]['activate'] = 1;
     self::$hook_count++;
     return;
    }
   array_push(self::$hooks, array(
     'name' => $name,
     'activate' => $activate
    )
   );
   self::$hook_count++;
  }
 
  public static function remove_hook($name) {
   $hooks = self::$hooks;
   $length = count($hooks);
   $name = str_replace('.php', '', $name);
   for ($i = 0; $i < $length; $i++) {
    if ($hooks[$i]['name'] !== $name)
     continue;
 
    self::$hook_count--;
    self::$hooks[$i]['activate'] = 0;
   }
  }
 
  public static function run_hooks($hook) {
   $plugins = self::$plugins;
   $hooks = self::$hooks;
   for ($i = 0; $i < count($hooks); $i++) {
    $plugin = self::getPluginObject($hooks[$i]['name']);
    if ($hooks[$i]['activate'] == 0) {
     $hook_defunc = 'deactivate_' . $hook;
     $func = is_callable(array($plugin, $hook_defunc));
     if (!$func) 
      $plugin->deactivate();
     else
      $plugin->$hook_defunc();
     continue;
    }
    
    $plugin_hooks = count($plugin->set_hooks());
    for ($i2 = 0; $i2 < $plugin_hooks; $i2++){
      $sPluginHook = isset($plugin_hooks[$i2])?$plugin_hooks[$i2]:'';
      if (strcasecmp($sPluginHook, $hook) != 0)
        continue;
    }
 
    $hook_func = 'activate_' . $hook; 
    $func = is_callable(array($plugin, $hook_func));
    if (!$func) 
     $plugin->deactivate();
    else
     $plugin->$hook_func();
   }
  }
 
  public static function getPluginObject($name) {
   $plugins = self::$plugins;
   $plugin_count = count($plugins);
   for ($i = 0; $i < $plugin_count; $i++) {
    if ($plugins[$i]['name'] !== $name)
     continue;
    return new $plugins[$i]['name']();
   }
 
   return null;
  }
 
  public static function getPluginCount() {
   return self::$plugin_count;
  }
 
  public static function getHookCount() {
   return self::$hook_count;
  }
 
  public static function get_plugins() {
   return self::$plugins;
  }
 
  public static function get_hooks() {
   return self::$hooks;
  }
 
 }