<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
    $language = $conn->real_escape_string($_POST['language'] ?? 'ru');
    $_SESSION['settings'] = [
        'email_notifications' => $email_notifications,
        'sms_notifications' => $sms_notifications,
        'language' => $language
    ];
    
    $_SESSION['success'] = "Настройки успешно сохранены!";
    header("Location: account.php");
    exit();
}
?>