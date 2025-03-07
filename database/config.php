<?php

// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "student-register"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//server mariadb

// $servername = getenv('localhost');
// $username = getenv('root');
// $password = getenv('');
// $dbname = getenv('student-register');

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Database connection settings
// $dbHost = 'localhost';
// $dbName = 'student-register';
// $dbUser = 'root';
// $dbPass = '';

// try {
//     $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
//     // set the PDO error mode to exception
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }
?>