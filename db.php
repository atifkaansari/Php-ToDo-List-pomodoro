<?php
$host = 'localhost';
$db   = 'todo_list';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$db = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>