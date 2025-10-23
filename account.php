<?php
include("include/header.php");

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}


function getInitials($name)
{
    $names = explode(' ', $name);
    $initials = '';
    foreach ($names as $n) {
        $initials .= strtoupper(substr($n, 0, 1));
    }
    return $initials;
}

$username = $_SESSION['username'];
$initials = getInitials($username);

// var_dump($_SESSION);
// exit();
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
                <a href="" class="menu-item active" data-tab="overview"><i class="fa-solid fa-chart-simple"></i>
                    Обзор</a>
                <a href="" class="menu-item" data-tab="orders"><i class="fa-solid fa-boxes-stacked"></i> Мои заказы</a>
                <a href="" class="menu-item" data-tab="history"><i class="fa-solid fa-clock-rotate-left"></i> История
                    заказов</a>
                <a href="" class="menu-item" data-tab="payment"><i class="fa-solid fa-credit-card"></i> Оплата и
                    счета</a>
                <a href="" class="menu-item" data-tab="profile"><i class="fa-solid fa-address-card"></i> Профиль</a>
                <a href="" class="menu-item" data-tab="settings"><i class="fa-solid fa-gear"></i> Настройки</a>
                <a href="" class="menu-item" data-tab="help"><i class="fa-solid fa-question"></i> Помощь</a>
                <a href="logout.php" class="menu-item"><i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Выйти</a>
            </div>
        </div>
    </aside>
    <div class="account-main">
        <div class="tab-content active" id="overview">
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
            <div class="table">
                <h2 class="account-table-header">Последние заказы</h2>
                <div class="account-table">
                    <table class="iksweb">
                        <tr>
                            <th>№ Заказа</th>
                            <th>Маршрут</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                        </tr>
                        <tr>
                            <td>#GT-2023-0456</td>
                            <td>Москва - New York</td>
                            <td>11.09.2001</td>
                            <td>Доставлен</td>
                            <td>1000</td>
                        </tr>
                        <tr>
                            <td>#GT-2023-0456</td>
                            <td>Москва - Киев</td>
                            <td>24.02.2022</td>
                            <td>В пути</td>
                            <td>1000</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-content" id="orders">
            0
        </div>
        <div class="tab-content" id="history">
            1
        </div>
        <div class="tab-content" id="payment">
            2
        </div>
        <div class="tab-content" id="profile">
            <div class="setting">
                <h2 class="profile-header">Настройки профиля</h2>
                <div class="profile-main">
                    <div>
                        <label for="name">ФИО</label>
                        <input type="text" id="name">
                        <label for="email">Email</label>
                        <input type="text" id="email">
                    </div>
                    <div>
                        <label for="number">Телефон</label>
                        <input type="text" id="number">
                        <label for="company">Компания</label>
                        <input type="text" id="company">
                    </div>
                </div>
                <h3 class="profile-header-textarea">Адрес</h3>
                <textarea name="" id=""></textarea>
                <button class="profile-button">Сохранить изминения</button>
            </div>
        </div>
        <div class="tab-content" id="settings">
            4
        </div>
        <div class="tab-content" id="help">
            5
        </div>
</section>
<?php
include("include/footer.php");
?>