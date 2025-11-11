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
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div class="account-info">
                    <p>Администратор</p>
                </div>
            </div>
            <hr>
            <div class="account-menu">
                <a href="" class="menu-item active" data-tab="orders"><i class="fa-solid fa-boxes-stacked"></i>
                    Управление<br> заказами</a>
                <a href="" class="menu-item" data-tab="users"><i class="fa-solid fa-users"></i> Управление<br>
                    пользователями</a>
                <a href="" class="menu-item" data-tab="transport"><i class="fa-solid fa-truck"></i>Управление<br>
                    транспортом</a>
                <a href="" class="menu-item" data-tab="services"><i class="fa-solid fa-gears"></i>Управление<br>
                    услугами</a>
                <a href="logout.php" class="menu-item exit"><i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Выйти</a>
            </div>
        </div>
    </aside>
    <div class="account-main">
        <div class="tab-content active" id="orders">
            <p class="admin-header">Управление заказами</p>
            <div class="admin-header-grid">
                <p>Статус:</p>
                <p>Дата от:</p>
                <p>Дата до:</p>
                <select id="pet-select">
                    <option value="All-statuses">Все статусы</option>
                    <option value="On-the-way">В пути</option>
                    <option value="Waiting">В ожидании</option>
                    <option value="Delivered">Доставлен</option>
                </select>
                <input id="date" type="date" value="дд.мм.гггг." />
                <input id="date" type="date" value="дд.мм.гггг." />
                <div class="admin-header-a">
                    <a class="apply" href="">Применить</a>
                    <a class="reset" href="">Сбросить</a>
                </div>
            </div>
            <table class="orders">
                <tr class="tr">
                    <td class="td-header">ID заказа</td>
                    <td class="td-header">Клиент</td>
                    <td class="td-header">Маршрут</td>
                    <td class="td-header">Статус</td>
                    <td class="td-header">Стоимость</td>
                    <td class="td-header">Действие</td>
                </tr>
                <tr>
                    <td>#7777</td>
                    <td>Дмитрий Забин</td>
                    <td>Москва - Киев</td>
                    <td class="status"><span class="On-the-way">В пути</span></td>
                    <td>15000 ₽</td>
                    <td class="actions"><i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i></td>
                </tr>
                <tr>
                    <td>#7777</td>
                    <td>Иван Петров</td>
                    <td>СПБ - Минск</td>
                    <td class="status"><span class="Delivered">Доставлен</span></td>
                    <td>10000 ₽</td>
                    <td class="actions"><i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i></td>
                </tr>
                <tr>
                    <td>#т435ес</td>
                    <td>Кирилл Троянович</td>
                    <td>Сервис - Гараж</td>
                    <td class="status"><span class="Waiting">В ожидании</span></td>
                    <td>180000 ₽</td>
                    <td class="actions"><i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i></td>
                </tr>
            </table>
            <div>

            </div>
        </div>
        <div class="tab-content" id="users">
            <p class="admin-header">Управление пользователями</p>
            <table class="orders">
                <tr class="tr">
                    <td class="td-header">ID</td>
                    <td class="td-header">Имя пользователя</td>
                    <td class="td-header">Email</td>
                    <td class="td-header">Телефон</td>
                    <td class="td-header">Дата регистрации</td>
                    <td class="td-header">Действие</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Дмитрий Забин</td>
                    <td>nshvavnasf@gmail.com</td>
                    <td>89876543210</td>
                    <td>05.11.2025</td>
                    <td class="actions"><i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Иван Петров</td>
                    <td>nshvavnasf@gmail.com</td>
                    <td>89876543210</td>
                    <td>05.11.2025</td>
                    <td class="actions"><i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i></td>
                </tr>
            </table>
        </div>
        <div class="tab-content" id="transport">
            <p class="admin-header">Управление транспортом</p>
            <div class="transport-cars-cart">
                <div class="cars-cart">
                    <i class="fa-solid fa-truck"></i>
                    <p class="cars-cart-name">Газель</p>
                    <p class="cars-cart-kg">1,500 кг</p>
                    <p class="cars-cart-status-available">Доступен</p>
                </div>
                <div class="cars-cart">
                    <i class="fa-solid fa-truck-moving"></i>
                    <p class="cars-cart-name">Бычок</p>
                    <p class="cars-cart-kg">3,000 кг</p>
                    <p class="cars-cart-status-available">Доступен</p>
                </div>
                <div class="cars-cart">
                    <i class="fa-solid fa-truck-front"></i>
                    <p class="cars-cart-name">Фура</p>
                    <p class="cars-cart-kg">20,000 кг</p>
                    <p class="cars-cart-status-busy">Занят</p>
                </div>
                <div class="cars-cart-add">
                    <i class="fa-solid fa-plus"></i>
                    <p class="cars-cart-add-add">Добавить</p>
                    <p class="cars-cart-add-new">новый транспорт</p>
                </div>
            </div>
        </div>
        <div class="tab-content" id="services">
            <p class="admin-header">Управление услугами</p>
            <div class="services-cart">
                <div>
                    <p class="services-header">Погрузко-разгрузочные Работы</p>
                    <p class="services-inscription">Помощь в погрузкеи разгрузке груза</p>
                </div>
                <div class="services-change">
                    <p class="services-price">1,500 ₽</p><i class="fa-solid fa-pen-to-square"></i><i
                        class="fa-solid fa-trash"></i>
                </div>
            </div>
            <div class="services-cart">
                <div>
                    <p class="services-header">Страхование груза</p>
                    <p class="services-inscription">Полное страхование груза на время перевозки</p>
                </div>
                <div class="services-change">
                    <p class="services-price">500 ₽</p><i class="fa-solid fa-pen-to-square"></i><i
                        class="fa-solid fa-trash"></i>
                </div>
            </div>
            <div class="services-cart">
                <div>
                    <p class="services-header">Упаковка</p>
                    <p class="services-inscription">Професиональная упаковка груза</p>
                </div>
                <div class="services-change">
                    <p class="services-price">800 ₽</p><i class="fa-solid fa-pen-to-square"></i><i
                        class="fa-solid fa-trash"></i>
                </div>
            </div>
            <div class="services-cart-add">
                <i class="fa-solid fa-plus"></i>
                <p class="services-cart-add-new">Добавить новую услугу</p>
            </div>
        </div>
    </div>