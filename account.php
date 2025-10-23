<?php
session_start();

if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

include("include/header.php");

function getInitials($name) {
    $names = explode(' ', $name);
    $initials = '';
    foreach ($names as $n) {
        $initials .= strtoupper(substr($n, 0, 1));
    }
    return $initials;
}

$username = $_SESSION['username'];
$initials = getInitials($username);
?>

<section class="account-container">
    <aside>
        <div>
            <div class="account-header">
                <div class="account-image">
                    <?php echo $initials; ?>
                </div>
                <div class="account-info">
                    <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <p class="account-email"><?php echo htmlspecialchars($_SESSION['Email']); ?></p>
                </div>
            </div>
            <hr>
            <div class="account-menu">
                <a href="" class="menu-item active"><i class="fa-solid fa-chart-simple"></i> Обзор</a>
                <a href="" class="menu-item"><i class="fa-solid fa-boxes-stacked"></i> Мои заказы</a>
                <a href="" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> История заказов</a>
                <a href="" class="menu-item"><i class="fa-solid fa-credit-card"></i> Оплата и счета</a>
                <a href="" class="menu-item"><i class="fa-solid fa-address-card"></i> Профиль</a>
                <a href="" class="menu-item"><i class="fa-solid fa-gear"></i> Настройки</a>
                <a href="" class="menu-item"><i class="fa-solid fa-question"></i> Помощь</a>
                <a href="logout.php" class="menu-item"><i class="fa-solid fa-arrow-right-from-bracket"></i> Выйти</a>
            </div>
        </div>
    </aside>
    <div class="account-main">
        <h1 class="account-main-header">Личный кабинет</h1>
        <div class="account-main-container">
            <div class="main-container">
                <p class="main-container-number">5</p>
                <p class="main-container-inscription">Активных заказов</p>
            </div>
            <div class="main-container">
                <p class="main-container-number">24</p>
                <p class="main-container-inscription">Всего заказов</p>
            </div>
            <div class="main-container">
                <p class="main-container-number">4.8</p>
                <p class="main-container-inscription">Рейтинг клиента</p>
            </div>
        </div>
    </div>
</section>
<?php
include("include/footer.php");
?> 