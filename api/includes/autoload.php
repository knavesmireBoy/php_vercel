<?php
function autoloader($className) {
    $fileName = str_replace('\\', '/', $className) . '.php';
    $file =  '../classes/' . $fileName;
    require_once $file;
}

spl_autoload_register('autoloader');
session_start();