<?php
$user = "root";
<<<<<<< HEAD
$pass = "";
=======
$pass = "root";
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
$dbname = "gruz";
$host = "localhost";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: ".$conn->connect_error);
}
?>