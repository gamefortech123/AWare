<?php

session_start();

include('globals.php');

try
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

}
catch (PDOException $ex)
{
    echo $ex->getMessage();
    die();
}

$link = 'http' . isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '';

$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI']; 

?>