<?php
/**
 * Index file
 *
 * @author Shameer
 */
error_reporting(E_ALL);
require 'Plugin.php';
require 'Application.php';
require 'Newsletter.php';


// require'Autoload.php';
$app = new Application();
$app->run();
