<?php
class Affector {
public function makeQuery($st, $msg){
       try {
       return $st->execute();
    }
    catch(PDOException $e){
        $error = $msg . ' ' . $e->getMessage();
        include 'error.html.php';
        exit();
    } 
}
}