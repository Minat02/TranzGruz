<?php
session_start();
include ("functions.php");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>ГрузТрансЛогистик</title>
</head>

<body>
    <header class="header">
        <div class="brand">
            <img src="assets/img/logo.png">
            <h1>ГрузТрансЛогистик</h1>
        </div>

        <nav class="navigation">
            <ul class="nav-items">
                <li><a href="index.php">Главная</a></li>
                <li><a href="#advantages">О компании</a></li>
                <li><a href="#services">Услуги</a></li>
                <li><a href="#contacts">Контакты</a></li>
            </ul>
        </nav>
        <div class="account-section">
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']):?>
            <a class="account-btn" href="account.php">Личный кабинет</a>
            <?php else: ?>
                <a class="account-btn" href="#" onclick="openModal(); return false;">Личный кабинет</a>
            <?php endif; ?>
        </div>
            </header>

<div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <?php if (isset($_SESSION['error'])): ?>
                <div style="color: red; margin-bottom: 10px; padding: 10px; background: #ffe6e6; border-radius: 4px;">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div style="color: green; margin-bottom: 10px; padding: 10px; background: #e6ffe6; border-radius: 4px;">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" class="active" method="post" action="auth.php">
                <h2 class="modal-autreg">Авторизация</h2>
                <input type="text" name="username" placeholder="Имя пользователя" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button class="modal-button" type="submit" name="login">Войти</button>
                <div class="switch-form">
                    Нет аккаунта? <a onclick="showRegistrationForm()">Зарегистрироваться</a>
                </div>
            </form>
            
            <form id="registrationForm" method="post" action="auth.php">
                <h2 class="modal-autreg">Регистрация</h2>
                <input type="text" name="username" placeholder="Имя пользователя" required>
                <input type="Email" name="Email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button class="modal-button" type="submit" name="register">Зарегистрироваться</button>
                <div class="switch-form">
                    Уже есть аккаунт? <a onclick="showLoginForm()">Войти</a>
                </div>
            </form>
        </div>
    </div>
<script src="assets/js/script.js"></script>