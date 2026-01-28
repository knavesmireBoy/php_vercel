<?php
function makeQuery($conn, $sql, $msg){
       if($conn instanceof PDO){
           $class = $conn instanceof PDO ? 'selector' : 'affector';
       }
    else {
        $msg = null;
    }
        $class = ucfirst($class);
        //require_once str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        require_once "../klass/$class.php";
        $q = new $class($conn, $sql, $msg);
        return $q->makeQuery($conn, $sql, $msg);
}
