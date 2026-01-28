<?php
class Selector {
public function makeQuery($conn, $sql, $msg){
          try {
              return $conn->query($sql);
          }
    catch(PDOException $e){
        $error = $msg . ' ' . $e->getMessage();
        include __DIR__ . '/../templates/error.html.php';
        exit();
    } 
}
}