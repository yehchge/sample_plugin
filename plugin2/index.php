<?php
 
require('Plugins.php');
 
echo '<html>
<head></head>

<body><p>lol penis</p>';

$plugins = new Plugins();
$plugins->run_hooks('index_content');
echo '<hr />';

$plugins->run_hooks('index_bottom');
echo '<p>cawk</p></body>

</html>';