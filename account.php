<?php
include("include/header.php");

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
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
                    <div class="account-table-cart">
                        <i class="fa-solid fa-circle-check"></i>
                        <p>
                            Заказ №2845 Доставлен<br>
                            Сегодня, 10:15
                        </p>
                    </div>
                    <div class="account-table-cart">
                        <i class="fa-solid fa-truck-fast"></i>
                        <p>
                            Заказ №2845 В пути<br>
                            Сегодня, 14:30
                        </p>
                    </div>
                    <div class="account-table-cart">
                        <i class="fa-solid fa-box-open"></i>
                        <p>
                            Заказ №2845 Принят в обработку<br>
                            Вчера, 16:45
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="orders">
            <h1 class="account-main-header">Личный кабинет</h1>
        </div>

        <div class="tab-content" id="history">
            <h1 class="account-main-header">Личный кабинет</h1>
            <h2 class="history-inscription">История заказов</h2>
            <table class="iksweb">
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>
        </div>

        <div class="tab-content" id="payment">
            <h1 class="account-main-header">Личный кабинет</h1>
        </div>

        <div class="tab-content" id="profile">
            <h1 class="account-main-header">Личный кабинет</h1>
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
            <h1 class="account-main-header">Личный кабинет</h1>
        </div>
        
        <div class="tab-content" id="help">
            <h1 class="account-main-header">Личный кабинет</h1>
        </div>
</section>
<?php
include("include/footer.php");
?>