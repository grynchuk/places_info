<?php
require __DIR__ . '/vendor/autoload.php';
$config = include 'config.php';

(new app\Instance($config))->run();

?>