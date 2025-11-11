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
                <a href="logout.php" class="menu-item exit"><i class="fa-solid fa-arrow-right-from-bracket"></i>
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
            <h2 class="account-orders-header">Активные заказы</h2>
            <div class="account-orders-cart">
                <div class="orders-cart">
                    <div>
                        <div class="orders-cart-header">
                            <p class="orders-cart-header-left">Заказ №7439</p>
                            <div class="orders-cart-header-right">В пути</div>
                        </div>
                        <div class="orders-cart-inscription">
                            <p><b>Маршрут:</b> Москва - Киев</p>
                            <p><b>Груз:</b> Электроника, 150кг</p>
                            <p><b>Ожидаемая доставка:</b> 15.12.2025</p>
                        </div>
                    </div>
                    <div class="delivery-status">
                        <div class="status-bar">
                            <div class="progress-line"></div>
                            <div class="progress-fill"></div>
                            <div class="status-step completed">
                                <div class="step-label">Принят</div>
                            </div>
                            <div class="status-step active">
                                <div class="step-label">В пути</div>
                            </div>
                            <div class="status-step">
                                <div class="step-label">Доставлен</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="history">
            <h1 class="account-main-header">Личный кабинет</h1>
            <h2 class="history-inscription">История заказов</h2>
            <table class="iksweb">
                <tr class="history-table-header">
                    <td>Номер заказа</td>
                    <td>Дата</td>
                    <td>Маршрут</td>
                    <td>Стоимость</td>
                    <td>Статус</td>
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
            <h2 class="account-table-header">Способы оплаты</h2>
            <div class="account-payment-cart">
                <div class="payment-cart">
                    <p><i class="fa-solid fa-credit-card"></i> Банковская карта</p>
                    <p>**** **** **** 1234</p>
                    <button>Изменить</button>
                </div>
                <div class="payment-cart">
                    <p><i class="fa-solid fa-building-columns"></i> Банковский перевод</p>
                    <p>Т-Банк</p>
                    <button>Изменить</button>
                </div>
            </div>
            <h2 class="account-table-header">История платежей</h2>
            <div class="account-history-cart">
                <div>
                    <p class="account-payment-header">Оплата заказа №1234</p>
                    <p class="account-payment-inscription">10.10.2025</p>
                </div>
                <div class="account-payment">
                    <p class="account-payment-header">15 000 ₽</p>
                    <p class="account-payment-inscription-p">Оплачено</p>
                </div>
            </div>
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
            <h2 class="setting-inscription">Настройки аккаунта</h2>
            <div class="setting-table-cart">
                <div>
                    <p class="setting-table-header">Уведомления по email</p>
                    <p class="setting-table-inscription">Получать уведомления о статусе заказов</p>
                </div>
                <div class="switch-button">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="setting-table-cart">
                <div>
                    <p class="setting-table-header">SMS уведомления</p>
                    <p class="setting-table-inscription">Получать SMS о доставке грузов</p>
                </div>
                <div class="switch-button">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="setting-table-cart">
                <div>
                    <p class="setting-table-header">Язык интерфейса</p>
                    <p class="setting-table-inscription">Выберите предпочитаемый язык</p>
                </div>
                <div class="switch-button">
                    <select id="pet-select">
                        <option value="Russia">Русский</option>
                        <option value="English">Английский</option>
                    </select>
                </div>
            </div>
            <div class="setting-table-footer">
                <p class="setting-table-footer-inscription">Опасная зона</p>
                <div>
                    <button class="setting-table-footer-b1">Удалить аккаунт</button>
                    <button class="setting-table-footer-b2">Архивировать данные</button>
                </div>
            </div>
        </div>

        <div class="tab-content" id="help">
            <h1 class="account-main-header">Личный кабинет</h1>
        </div>
    </div>
</section>