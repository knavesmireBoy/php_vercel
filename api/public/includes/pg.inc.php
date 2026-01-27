<?php
//phpinfo();

$params = ['host' => 'pgsql', 'port' => 5432, 'database' => 'cddb', 'user' => 'andrewjsykes', 'password' => 'covid19krauq'];

//$params = ['host' => 'ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech', 'port' => 5432, 'database' => 'cddb', 'user' => 'cddb_owner', 'password' => 'npg_FfpisZ9Nk8Jl'];
//options=endpoint%3D[ep-long-silence-ab8urerr-pooler]
//$params = ['host' => 'ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech', 'port' => 5432, 'database' => 'cddb', 'user' => 'cddb_owner:endpoint=ep-long-silence-ab8urerr-pooler', 'password' => 'npg_FfpisZ9Nk8Jl'];

//$db = 'postgresql://cddb_owner:endpoint=ep-long-silence-ab8urerr-pooler;npg_FfpisZ9Nk8Jl@ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech/cddb?sslmode=require&channel_binding=require';
try {
    $db = sprintf(
        "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
        $params['host'],
        $params['port'],
        $params['database'],
        $params['user'],
        $params['password']
    );

    //$db = 'postgresql://cddb_owner:npg_FfpisZ9Nk8Jl@ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech/cddb?options=endpoint=ep-long-silence-ab8urerr-pooler&sslmode=require&channel_binding=require';
    //dump($db);

    /*
    Unable to connect to the database server: SQLSTATE[08006] [7] ERROR: Endpoint ID is not specified. Either please upgrade the postgres client library (libpq) for SNI support or pass the endpoint ID (first part of the domain name) as a parameter: '?options=endpoint%3D'. See more at https://neon.tech/sni ERROR: connection is insecure (try using `sslmode=require`)
        postgresql://[user[:password]@][netloc][:port][/dbname][?param1=value1&...]

        */
    //   $db .= 'options=endpoint=ep-long-silence-ab8urerr-pooler';
    //$db .= '?sslmode=require';

    //$db = 'postgresql://cddb_owner:npg_FfpisZ9Nk8Jl@ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech/cddb?endpoint=ep-long-silence-ab8urerr-pooler';

    //$db = 'postgresql://cddb_owner:npg_FfpisZ9Nk8Jl@ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech:5432/cddb';
/*
    $db = 'pgsql:host=ep-long-silence-ab8urerr-pooler.eu-west-2.aws.neon.tech;port=5432;dbname=cddb;user=cddb_owner;password=npg_FfpisZ9Nk8Jl/cddb?options=endpoint%3Dep-long-silence-ab8urerr-pooler';
*/
    //pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
    
    $pdo = new \PDO($db);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET search_path TO cd_db');
    //$stmt->bindValue(':id', $id);
    //$stmt->execute();
    //return the result set as an object to interact with
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage();
    include './templates/output.html.php';
    exit();
}
