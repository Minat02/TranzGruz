<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $sql = "SELECT id, username, email, full_name, phone, role FROM users 
                WHERE username = '$username' AND password = '$password'";
        
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
            $_SESSION['logged_in'] = true;
            error_log("Пользователь " . $user['username'] . " вошел с ролью: " . $_SESSION['role']);
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: account.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Неверное имя пользователя или пароль!";
            header("Location: index.php");
            exit();
        }
    }

    if (isset($_POST['register'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['Email']);
        $password = $conn->real_escape_string($_POST['password']);

        $check_sql = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $_SESSION['error'] = "Пользователь с таким именем или email уже существует";
        } else {
            $sql = "INSERT INTO users (username, email, password, role) 
                    VALUES ('$username', '$email', '$password', 'user')";
            
            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "Регистрация успешна! Теперь войдите в аккаунт.";
            } else {
                $_SESSION['error'] = "Ошибка регистрации: " . $conn->error;
            }
        }
        
        header("Location: index.php");
        exit();
    }
}
header("Location: index.php");
?>