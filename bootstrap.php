<?php
function load($file_name) {
    if(file_exists($file_name)) {
        require_once($file_name);
    }
}
function loadModel($class) {
    $path = __DIR__ . '/src/Models/';
    load($path . $class .'.php');
}
function loadBase($class) {
    $path = __DIR__ . '/' ;
    load($path . $class .'.php');
}
spl_autoload_register('loadModel');
spl_autoload_register('loadBase');