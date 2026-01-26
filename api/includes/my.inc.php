<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=cd_db', 'root', 'covid19krauq');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
    //$conn = mysql_connect('localhost', 'root', 'krauq');
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage();
    include '../templates/output.html.php';
    exit();
}
/*
if (!mysql_select_db('cddb', $conn))
{
$error = 'Unable to locate the cds database.' . mysql_error();
include 'error.html.php';
exit();
}
*/
//$output = 'Database connection established.';
//include 'output.html.php';
