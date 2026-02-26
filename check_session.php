<?php
session_start();
echo "<h2>Информация о сессии:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Проверка наличия роли
if (isset($_SESSION['role'])) {
    echo "<p>Роль: " . $_SESSION['role'] . "</p>";
} else {
    echo "<p>Роль не установлена</p>";
}
?>