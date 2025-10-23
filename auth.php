<?php
session_start();
require_once('db.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
if(isset($_POST['login'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['Email'] = $user['Email'];
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
    if(isset($_POST['register'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['Email']);
        $password = $conn->real_escape_string($_POST['password']);

        $check_sql = "SELECT id FROM users WHERE username = '$username' OR Email = '$email'";
        $check_result = $conn->query($check_sql);
        
        if($check_result->num_rows  >  0) {
            $_SESSION['error'] = "Пользователь с таким именем или email уже существует";
        }else{
            $sql = "INSERT INTO users (username, Email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "Регистрация успешна! Теперь войдите в аккаунт.";
                header("Location: index.php");
                exit();
            }else{
                $_SESSION['error'] = "Ошибка регистрации: " . $conn->error;
            }
        }
    }
}
header("Location: index.php");
?>