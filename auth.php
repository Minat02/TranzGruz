<?php
session_start();
require_once('db.php');

<<<<<<< HEAD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
=======
if($_SERVER['REQUEST_METHOD'] == 'POST') {
if(isset($_POST['login'])) {
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

<<<<<<< HEAD
        if ($result->num_rows > 0) {
=======
        if($result->num_rows > 0) {
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
<<<<<<< HEAD
                $_SESSION['Email'] = $user['email'];
=======
                $_SESSION['Email'] = $user['Email'];
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
                $_SESSION['logged_in'] = true;

                header("Location: account.php");
                exit();
            } else {
                $_SESSION['error'] = "Неверный пароль!";
            }
        } else {
            $_SESSION['error'] = "Пользователь не найден";
        }
    }
<<<<<<< HEAD
    if (isset($_POST['register'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
=======
    if(isset($_POST['register'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['Email']);
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
        $password = $conn->real_escape_string($_POST['password']);

        $check_sql = "SELECT id FROM users WHERE username = '$username' OR Email = '$email'";
        $check_result = $conn->query($check_sql);
<<<<<<< HEAD

        if ($check_result->num_rows > 0) {
            $_SESSION['error'] = "Пользователь с таким именем или email уже существует";
        } else {
=======
        
        if($check_result->num_rows  >  0) {
            $_SESSION['error'] = "Пользователь с таким именем или email уже существует";
        }else{
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
            $sql = "INSERT INTO users (username, Email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "Регистрация успешна! Теперь войдите в аккаунт.";
                header("Location: index.php");
                exit();
<<<<<<< HEAD
            } else {
=======
            }else{
>>>>>>> 7d1e624cb249ea1ba9ea826acd1ab3176debd6f7
                $_SESSION['error'] = "Ошибка регистрации: " . $conn->error;
            }
        }
    }
}
header("Location: index.php");
?>