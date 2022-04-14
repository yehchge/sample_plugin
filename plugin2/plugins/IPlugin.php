<?php
 interface IPlugin {
  public function set_hooks();
  public function plugin_info();
  public function deactivate();
 }
