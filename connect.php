<?php

$host = "localhost";
$user = "ahassan17";
$password = "e5TQSNCrrN";
$dbname = "ahassan17";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>