<?php

class TestPlugin implements IPlugin {
 
    public function set_hooks() {
        return array(
            'index_bottom', 'index_content'
        );
    }

    public function plugin_info() {
        return array(
            'name' => 'Test Plugin',
            'version' => 1.1
        );
    }
 
    public function activate_index_bottom() {
        echo '<p>This will activate if the index_bottom hook is used</p>';
    }
 
    public function deactivate_index_bottom() {
        echo 'DEACTIVATE INDEX BOTTOM';
    }
 
    public function activate_index_content() {
        echo '<p><a href="lol.php" alt="">testplugin lolfags</a></p>';
    }
 
    public function deactivate_index_content() {
        echo 'DEACTIVATE';
    }
 
    // invoked if other hook functions fail for any reason
    public function deactivate() {
        echo '<h1>Plugin system broken!!?!?!?</h1>';
    }

}
