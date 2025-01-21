<?php

$host = 'localhost';
$dbname = 'booking_room';
$username = 'booking';
$password = 'booking';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Set default charset
    $pdo->query('SET NAMES utf8');

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
