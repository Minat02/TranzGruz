<?php
session_start();
require_once('db.php');


if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset ($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $sql = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone' WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['success'] = "Профиль успешно обновлен!";
    } else {
        $_SESSION['error'] = "Ошибка при обновлении профиля: " . $conn->error;
    }
    header("Location: account.php");
    exit();
}
?>