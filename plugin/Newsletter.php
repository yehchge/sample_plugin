<?php

/**
 * Newsletter plugin
 *
 * @author Shameer
 */
class Newsletter implements Plugin{
    public function init()
    {
        echo "Newsletter Plugin init";
    }
    public function adminMenu()
    {
        return array('Newsletter settings');
    }
}