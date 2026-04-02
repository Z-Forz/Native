<?php
$host = 'localhost';
$user = 'zforz';
$pass = '070609';
$db   = 'pt_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

session_start();
?>
