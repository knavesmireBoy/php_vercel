<?php
//phpinfo();
//scram-sha-256; md5
$params = ['host' => 'localhost', 'port' => 5432    , 'database' => 'cddb', 'user' => 'andrewjsykes', 'password' => 'covid19krauq'];
$params = ['host' => 'ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech', 'port' => 5432, 'database' => 'cddb', 'user' => 'cddb_owner', 'password' => 'npg_FfpisZ9Nk8Jl'];
$params = ['host' => 'ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech', 'port' => 5432, 'database' => 'cddb', 'user' => 'neondb_owner', 'password' => 'npg_fl2Ram6ULyJD'];

try {
    $db = sprintf(
        "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
        $params['host'],
        $params['port'],
        $params['database'],
        $params['user'],
        $params['password']
    );
    $pdo = new PDO($db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET search_path TO cd_db');
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage();
    include './templates/output.html.php';
    exit();
}
