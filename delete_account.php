<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $user_id = $_SESSION['user_id'];

    $conn->query("DELETE FROM order_services WHERE order_id IN (SELECT id FROM orders WHERE client_id = '$user_id')");
    $conn->query("DELETE FROM orders WHERE client_id = '$user_id'");

    $conn->query("DELETE FROM users WHERE id = '$user_id'");

    session_destroy();
    
    $_SESSION['success'] = "Аккаунт успешно удален!";
    header("Location: index.php");
    exit();
} else {

    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Удаление аккаунта</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div style="max-width: 600px; margin: 50px auto; padding: 20px; text-align: center;">
            <h2 style="color: red;">Подтверждение удаления аккаунта</h2>
            <p>Вы уверены, что хотите удалить свой аккаунт? Это действие невозможно отменить.</p>
            <p>Все ваши заказы и данные будут удалены безвозвратно.</p>
            
            <div style="margin-top: 30px;">
                <a href="delete_account.php?confirm=yes" 
                   style="background: red; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-right: 20px;">
                    Да, удалить аккаунт
                </a>
                <a href="account.php" 
                   style="background: #00CCBF; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                    Отмена
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>