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
            <hr class="order-hr">
            <div class="account-menu">
                <a href="" class="menu-item active" data-tab="overview"><i class="fa-solid fa-chart-simple"></i>
                    Обзор</a>
                <a href="" class="menu-item" data-tab="orders"><i class="fa-solid fa-boxes-stacked"></i> Мои заказы</a>
                <a href="" class="menu-item" data-tab="new_order"><i class="fa-solid fa-plus"></i> Новый заказ</a>
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
        <div class="tab-content" id="new_order">
            <div class="tab active" id="address">
                <div class="address">
                    <p class="address-header">Адресс отправления</p>
                    <div class="filling-cart">
                        <label for="country">Страна*</label>
                        <label for="city">Город*</label>
                        <label for="address">Адрес*</label>
                        <label for="postal_code">Почтовый индекс</label>
                        <input type="text" id="country">
                        <input type="text" id="city">
                        <input type="text" id="address">
                        <input type="text" id="postal_code">
                    </div>
                    <hr class="order-hr">
                    <p class="address-header-2">Адресс назначителя</p>
                    <div class="filling-cart">
                        <label for="country">Страна*</label>
                        <label for="city">Город*</label>
                        <label for="address">Адрес*</label>
                        <label for="postal_code">Почтовый индекс</label>
                        <input type="text" id="country">
                        <input type="text" id="city">
                        <input type="text" id="address">
                        <input type="text" id="postal_code">
                    </div>
                    <hr class="order-hr">
                    <div class="new_order_a">
                        <a href="" class="transition further" data-tab="cargo"> Далее</a>
                    </div>
                </div>
            </div>

            <div class="tab" id="cargo">
                <div class="cargo">
                    <div class="address">
                        <p class="address-header">Адресс отправления</p>
                        <div class="filling-cart">
                            <label for="country">Тип груза*</label>
                            <label for="city">Вес (кг)*</label>
                            <label for="address">Объём (м²)</label>
                            <label for="postal_code">Стоимость груза (₽)</label>
                            <div class="type">
                                <select id="pet-select">
                                    <option value="type">Выберите тип груза</option>
                                    <option value="Electronics">Электроника</option>
                                    <option value="Furniture">Мебель</option>
                                    <option value="Cloth">Одежда</option>
                                    <option value="Documents">Документы</option>
                                    <option value="Products">Продукты питания</option>
                                    <option value="Equipment">Оборудование</option>
                                    <option value="Construction_materials">Строительные материалы</option>
                                    <option value="Other">Другое</option>
                                </select>
                            </div>
                            <input type="text" id="city">
                            <input type="text" id="address">
                            <input type="text" id="postal_code">
                        </div>
                        <h3 class="cargo-text">Описание груза*</h3>
                        <textarea class="cargo-textarea" name="" id=""></textarea>
                        <div class="checkbox">
                            <input type="checkbox">
                            <p>Хрупкий груз (требует особой осторожности)</p>
                        </div>
                        <hr class="cargo-hr">
                        <p class="choice-car">Выбор транспорта</p>
                        <div class="choice-car-cart">
                            <div class="car-cart">
                                <i class="fa-solid fa-truck"></i>
                                <div>
                                    <p class="car-cart-name">Газель</p>
                                    <p class="car-cart-data">До 1,500 кг · 25₽/км</p>
                                    <p class="car-cart-description">Идеальна для мелких грузов перевозов</p>
                                </div>
                                <div class="car-cart-price">
                                    <p>15,000₽</p>
                                </div>
                            </div>
                            <div class="car-cart">
                                <i class="fa-solid fa-truck-moving"></i>
                                <div>
                                    <p class="car-cart-name">Бычок</p>
                                    <p class="car-cart-data">До 3,000 кг · 40₽/км</p>
                                    <p class="car-cart-description">Для средних грузов и комерческих перевозок</p>
                                </div>
                                <div class="car-cart-price">
                                    <p>24,000₽</p>
                                </div>
                            </div>
                            <div class="car-cart">
                                <i class="fa-solid fa-truck-front"></i>
                                <div>
                                    <p class="car-cart-name">Фура</p>
                                    <p class="car-cart-data">До 20,000 кг · 50₽/км</p>
                                    <p class="car-cart-description">Для крупных грузов и международных перевозок</p>
                                </div>
                                <div class="car-cart-price">
                                    <p>45,000₽</p>
                                </div>
                            </div>
                            <div class="car-cart">
                                <i class="fa-regular fa-snowflake"></i>
                                <div>
                                    <p class="car-cart-name">Рефрижератор</p>
                                    <p class="car-cart-data">До 15,000 кг · 75₽/км</p>
                                    <p class="car-cart-description">Для темпиратурных грузов и продуктов</p>
                                </div>
                                <div class="car-cart-price">
                                    <p>52,000₽</p>
                                </div>
                            </div>
                        </div>
                        <div class="new_order_a">
                            <a href="" class="transition back" data-tab="address"> Назад</a>
                            <a href="" class="transition further" data-tab="service"> Далее</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab" id="service">
                <div class="service">
                    <div class="address">
                        <p class="address-header">Дополнительные услуги</p>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Погрузо-разгрузочные работы</p>
                            <p class="service-cart-description">Помощь в загрузке и разгрузке груза</p>
                            <p class="service-cart-price">1,500 ₽</p>
                        </div>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Страхование груза</p>
                            <p class="service-cart-description">Полное страхование груза на время перевозки</p>
                            <p class="service-cart-price">500 ₽</p>
                        </div>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Упаковка</p>
                            <p class="service-cart-description">Профессиональная упаковка груза</p>
                            <p class="service-cart-price">800 ₽</p>
                        </div>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Срочная доставка</p>
                            <p class="service-cart-description">Доставка в ускоренном режиме</p>
                            <p class="service-cart-price">2,000 ₽</p>
                        </div>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Таможнное оформление</p>
                            <p class="service-cart-description">Оформление документов для международных перевозок</p>
                            <p class="service-cart-price">3,000₽</p>
                        </div>
                        <div class="service-cart">
                            <input type="checkbox">
                            <p class="service-cart-name">Временное хранение</p>
                            <p class="service-cart-description">Хранение груза на складе до 3 дней</p>
                            <p class="service-cart-price">1,000 ₽</p>
                        </div>
                        <div class="total-section">
                            <div class="total-row">
                                <span class="total-label">Дополнительные услуги:</span>
                                <span class="total-value">15,000 ₽</span>
                            </div>

                            <div class="total-row">
                                <span class="total-label">Скидка:</span>
                                <span class="total-value">0 ₽</span>
                            </div>

                            <div class="total-row final-total">
                                <span>Итого:</span>
                                <span>15,000 ₽</span>
                            </div>
                        </div>
                        <div class="new_order_a">
                            <a href="" class="transition back" data-tab="cargo"> Назад</a>
                            <a href="" class="transition further" data-tab="order"> Далее</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab" id="order">
                <div class="order">
                    <p class="address-header">Подтверждение заказа</p>
                    <div class="order-cart">
                        <p class="order-cart-header">Маршрут</p>
                        <hr class="order-hr">
                        <p class="order-cart-description">Отправление:</p>
                        <p class="order-cart-description">Адрес:</p>
                        <p class="order-cart-description">Назначение:</p>
                        <p class="order-cart-description">Адрес:</p>
                    </div>
                    <div class="order-cart">
                        <p class="order-cart-header">Груз</p>
                        <hr class="order-hr">
                        <p class="order-cart-description">Тип:</p>
                        <p class="order-cart-description">Вес:</p>
                        <p class="order-cart-description">Объём:</p>
                        <p class="order-cart-description">Стоимость:</p>
                        <p class="order-cart-description">Хрупкий:</p>
                        <p class="order-cart-description">Описание:</p>
                    </div>
                    <div class="order-cart">
                        <p class="order-cart-header">Транспорт</p>
                        <hr class="order-hr">
                        <p class="order-cart-description">Газель:</p>
                    </div>
                    <div class="order-cart">
                        <p class="order-cart-header">Услуги</p>
                        <hr class="order-hr">
                        <p class="order-cart-description">Нет дополнительных услуг</p>
                    </div>
                    <div class="order-cart end">
                        <p>Стоимость</p>
                        <hr class="order-hr">
                        <p>15,000 ₽</p>
                    </div>



                    <div class="contact-information">
                        <p class="address-header">Контактная информация</p>
                        <div class="filling-cart">
                            <label for="face">Контактное лицо*</label>
                            <label for="telephone">Телефон*</label>
                            <label for="email">Email*</label>
                            <label for="data">Желаемая дата доставки</label>
                            <input type="text" id="face">
                            <input type="text" id="telephone">
                            <input type="text" id="email">
                            <input id="date" type="date" value="дд.мм.гггг." />
                        </div>
                        <h3 class="cargo-text">Особые указание</h3>
                        <textarea class="cargo-textarea" name="" id=""></textarea>
                        <div class="contact-information-checkbox">
                            <input type="checkbox">
                            <p>Я согласен с <a href="#">условиями перевозки</a> и <a href="#">политической конфиденциальности</a>*</p>
                        </div>
                        <hr class="cargo-hr">
                    </div>




                    <div class="new_order_a">
                        <a href="" class="transition back" data-tab="service"> Назад</a>
                        <a href="" class="transition last" data-tab="order"> Оформление заказа</a>
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
                <h3 class="profile-header-text">Адрес</h3>
                <textarea class="profile-header-textarea" name="" id=""></textarea>
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