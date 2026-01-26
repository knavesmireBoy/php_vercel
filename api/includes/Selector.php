<?php
class Selector {
public function makeQuery($conn, $sql, $msg){
          try {
              return $conn->query($sql);
          }
    catch(PDOException $e){
        $error = $msg . ' ' . $e->getMessage();
        include 'error.html.php';
        exit();
    } 
}
}