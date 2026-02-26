<?php
$user = "root";
$pass = "root";
$dbname = "gruz";
$host = "localhost";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: ".$conn->connect_error);
}
?>