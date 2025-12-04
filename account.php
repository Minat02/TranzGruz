<?php
include("include/header.php");

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$full_name = $_SESSION['full_name'] ?? '';
$phone = $_SESSION['phone'] ?? '';
$initials = getInitials($username);

$stats_sql = $conn->prepare("SELECT COUNT(*) as total_orders, SUM(CASE WHEN status != 'Доставлен' THEN 1 ELSE 0 END) as active_orders FROM orders WHERE client_id = ?");
$stats_sql->bind_param("i", $user_id);
$stats_sql->execute();
$stats_result = $stats_sql->get_result();
$stats = $stats_result->fetch_assoc();
$stats_sql->close();

$recent_orders_sql = $conn->prepare("SELECT * FROM orders WHERE client_id = ? ORDER BY create_date DESC LIMIT 3");
$recent_orders_sql->bind_param("i", $user_id);
$recent_orders_sql->execute();
$recent_orders_result = $recent_orders_sql->get_result();
$recent_orders_sql->close();

$active_orders_sql = $conn->prepare("SELECT * FROM orders WHERE client_id = ? AND status != 'Доставлен' ORDER BY create_date DESC");
$active_orders_sql->bind_param("i", $user_id);
$active_orders_sql->execute();
$active_orders_result = $active_orders_sql->get_result();
$active_orders_sql->close();

$all_orders_sql = "SELECT * FROM orders WHERE client_id = '$user_id' ORDER BY create_date DESC";
$all_orders_result = $conn->query($all_orders_sql);

$vehicles_sql = "SELECT * FROM vehicles";
$vehicles_result = $conn->query($vehicles_sql);
$vehicles_data = [];
while($v = $vehicles_result->fetch_assoc()) {
    $vehicles_data[] = $v;
}

$services_sql = "SELECT * FROM services";
$services_result = $conn->query($services_sql);
$services_data = [];
while($s = $services_result->fetch_assoc()) {
    $services_data[] = $s;
}

$payments_sql = $conn->prepare("SELECT o.id as order_id, o.total_price, o.create_date FROM orders o WHERE o.client_id = ? ORDER BY o.create_date DESC LIMIT 5");
$payments_sql->bind_param("i", $user_id);
$payments_sql->execute();
$payments_result = $payments_sql->get_result();
$payments_sql->close();
?>

<section class="account-container">
    <aside>
        <div>
            <div class="account-header">
                <div class="account-image">
                    <?php echo $initials; ?>
                </div>
                <div class="account-info">
                    <p><?php echo htmlspecialchars($username); ?></p>
                    <p class="account-email"><?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
            <hr class="order-hr">
<div class="account-menu">
    <a href="#" class="menu-item active" data-tab="overview"><i class="fa-solid fa-chart-simple"></i> Обзор</a>
    <a href="#" class="menu-item" data-tab="orders"><i class="fa-solid fa-boxes-stacked"></i> Мои заказы</a>
    <a href="#" class="menu-item" data-tab="new_order"><i class="fa-solid fa-plus"></i> Новый заказ</a>
    <a href="#" class="menu-item" data-tab="history"><i class="fa-solid fa-clock-rotate-left"></i> История заказов</a>
    <a href="#" class="menu-item" data-tab="payment"><i class="fa-solid fa-credit-card"></i> Оплата и счета</a>
    <a href="#" class="menu-item" data-tab="profile"><i class="fa-solid fa-address-card"></i> Профиль</a>
    <a href="#" class="menu-item" data-tab="settings"><i class="fa-solid fa-gear"></i> Настройки</a>
    <a href="logout.php" class="menu-item exit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Выйти</a>
</div>
        </div>
    </aside>
    <div class="account-main">
        
        <div class="tab-content active" id="overview">
            <h1 class="account-main-header">Личный кабинет</h1>
            <div class="account-main-container">
                <div class="main-container">
                    <p class="main-container-number"><?php echo $stats['active_orders'] ?? 0; ?></p>
                    <p class="main-container-inscription">Активных заказов</p>
                </div>
                <div class="main-container">
                    <p class="main-container-number"><?php echo $stats['total_orders'] ?? 0; ?></p>
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
                    <?php if ($recent_orders_result->num_rows > 0): ?>
                        <?php while($order = $recent_orders_result->fetch_assoc()): ?>
                            <div class="account-table-cart">
                                <i class="fa-solid 
                                    <?php 
                                    switch($order['status']) {
                                        case 'Доставлен': echo 'fa-circle-check'; break;
                                        case 'В пути': echo 'fa-truck-fast'; break;
                                        default: echo 'fa-box-open';
                                    }
                                    ?>
                                "></i>
                                <p>
                                    Заказ №<?php echo $order['id']; ?> <?php echo $order['status']; ?><br>
                                    <?php echo date('d.m.Y', strtotime($order['create_date'])); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Заказов пока нет</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab-content" id="orders">
            <h1 class="account-main-header">Активные заказы</h1>
            <div class="account-orders-cart">
                <?php if ($active_orders_result->num_rows > 0): ?>
                    <?php while($order = $active_orders_result->fetch_assoc()):
                        $order_services_sql = $conn->prepare("SELECT s.name FROM order_services os JOIN services s ON os.service_id = s.id WHERE os.order_id = ?");
                        $order_services_sql->bind_param("i", $order['id']);
                        $order_services_sql->execute();
                        $order_services_result = $order_services_sql->get_result();
                        $services_list = [];
                        while($service = $order_services_result->fetch_assoc()) {
                            $services_list[] = $service['name'];
                        }
                        $order_services_sql->close();
                    ?>
                        <div class="orders-cart">
                            <div>
                                <div class="orders-cart-header">
                                    <p class="orders-cart-header-left">Заказ №<?php echo $order['id']; ?></p>
                                    <div class="orders-cart-header-right"><?php echo $order['status']; ?></div>
                                </div>
                                <div class="orders-cart-inscription">
                                    <p><b>Маршрут:</b> <?php echo $order['from_address']; ?> - <?php echo $order['to_address']; ?></p>
                                    <p><b>Груз:</b> <?php echo $order['cargo_type']; ?>, <?php echo $order['cargo_weight']; ?>кг</p>
                                    <?php if (!empty($services_list)): ?>
                                        <p><b>Услуги:</b> <?php echo implode(', ', $services_list); ?></p>
                                    <?php endif; ?>
                                    <p><b>Стоимость:</b> <?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽</p>
                                </div>
                            </div>
                            <?php if ($order['status'] == 'В пути'): ?>
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
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Активных заказов нет</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-content" id="new_order">
            <h1 class="account-main-header">Новый заказ</h1>
            <form action="order_handler.php" method="POST" id="orderForm">
                <input type="hidden" name="step" value="1">

                <div class="order-steps" id="orderFormContainer">
                    <div class="order-step active" id="address_step">
                        <p class="address-header">Адрес отправления</p>
                        <div class="filling-cart">
                            <input type="text" name="from_country" placeholder="Страна*" required>
                            <input type="text" name="from_city" placeholder="Город*" required>
                            <input type="text" name="from_address" placeholder="Адрес*" required>
                            <input type="text" name="from_postal" placeholder="Почтовый индекс">
                        </div>
                        <hr class="order-hr">
                        <p class="address-header-2">Адрес назначения</p>
                        <div class="filling-cart">
                            <input type="text" name="to_country" placeholder="Страна*" required>
                            <input type="text" name="to_city" placeholder="Город*" required>
                            <input type="text" name="to_address" placeholder="Адрес*" required>
                            <input type="text" name="to_postal" placeholder="Почтовый индекс">
                        </div>
                        <div class="new_order_a">
                            <button type="button" class="transition further" onclick="validateStep(1)">Далее</button>
                        </div>
                    </div>

                    <div class="order-step" id="cargo_step">
                        <div class="address">
                            <p class="address-header">Информация о грузе</p>
                            <div class="filling-cart">
                                <div class="type">
                                    <select name="cargo_type" id="cargo_type" required>
                                        <option value="">Выберите тип груза*</option>
                                        <option value="Электроника">Электроника</option>
                                        <option value="Мебель">Мебель</option>
                                        <option value="Одежда">Одежда</option>
                                        <option value="Документы">Документы</option>
                                        <option value="Продукты питания">Продукты питания</option>
                                        <option value="Оборудование">Оборудование</option>
                                        <option value="Строительные материалы">Строительные материалы</option>
                                        <option value="Другое">Другое</option>
                                    </select>
                                </div>
                                <input type="text" name="cargo_weight" id="cargo_weight" placeholder="Вес (кг)*" required>
                                <input type="text" name="cargo_volume" id="cargo_volume" placeholder="Объём (м³)">
                                <input type="text" name="cargo_value" id="cargo_value" placeholder="Стоимость груза (₽)">
                            </div>
                            <h3 class="cargo-text">Описание груза*</h3>
                            <textarea class="cargo-textarea" name="cargo_description" id="cargo_description" placeholder="Подробное описание груза" required></textarea>
                            <div class="checkbox">
                                <input type="checkbox" name="is_fragile" id="is_fragile" value="1">
                                <p>Хрупкий груз (требует особой осторожности)</p>
                            </div>
                            <hr class="cargo-hr">
                            <p class="choice-car">Выбор транспорта</p>
                            <div class="choice-car-cart" id="vehicles_container">
                                <?php foreach($vehicles_data as $vehicle): ?>
                                    <div class="car-cart" data-vehicle-id="<?php echo $vehicle['id']; ?>" data-vehicle-name="<?php echo htmlspecialchars($vehicle['name']); ?>" data-vehicle-price="<?php echo $vehicle['price']; ?>" onclick="selectVehicleFromCart(this)">
                                        <i class="fa-solid fa-truck"></i>
                                        <div>
                                            <p class="car-cart-name"><?php echo $vehicle['name']; ?></p>
                                            <p class="car-cart-data">До <?php echo $vehicle['capacity']; ?></p>
                                            <p class="car-cart-description"><?php echo $vehicle['name']; ?> для перевозок</p>
                                        </div>
                                        <div class="car-cart-price">
                                            <p><?php echo isset($vehicle['price']) ? number_format($vehicle['price'], 0, '', ' ') . '₽' : 'Цена не указана'; ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="vehicle_id" id="vehicle_id" required>
                            <input type="hidden" name="vehicle_name" id="vehicle_name">
                            <input type="hidden" name="vehicle_price" id="vehicle_price">
                            <div class="new_order_a">
                                <button type="button" class="transition back" onclick="prevStep(1)">Назад</button>
                                <button type="button" class="transition further" onclick="validateStep(2)">Далее</button>
                            </div>
                        </div>
                    </div>

                    <div class="order-step" id="service_step">
                        <div class="address">
                            <p class="address-header">Дополнительные услуги</p>
                            <div id="services_container">
                                <?php foreach($services_data as $service): ?>
                                    <div class="service-cart">
                                        <input type="checkbox" name="services[]" value="<?php echo $service['id']; ?>"
                                               data-price="<?php echo $service['price']; ?>"
                                               onchange="calculateTotal()">
                                        <p class="service-cart-name"><?php echo $service['name']; ?></p>
                                        <p class="service-cart-description"><?php echo $service['name']; ?></p>
                                        <p class="service-cart-price"><?php echo number_format($service['price'], 0, '', ' '); ?> ₽</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="total-section">
                                <div class="total-row">
                                    <span class="total-label">Транспорт:</span>
                                    <span class="total-value" id="vehicle-price-display">0 ₽</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">Услуги:</span>
                                    <span class="total-value" id="services-price-display">0 ₽</span>
                                </div>
                                <div class="total-row final-total">
                                    <span>Итого:</span>
                                    <span id="total-price-display">0 ₽</span>
                                </div>
                            </div>
                            <input type="hidden" name="total_price" id="total_price" value="0">
                            <div class="new_order_a">
                                <button type="button" class="transition back" onclick="prevStep(2)">Назад</button>
                                <button type="button" class="transition further" onclick="validateStep(3)">Далее</button>
                            </div>
                        </div>
                    </div>

                    <div class="order-step" id="confirm_step">
                        <div class="order">
                            <p class="address-header">Подтверждение заказа</p>

                            <div class="order-cart">
                                <p class="order-cart-header">Маршрут</p>
                                <hr class="order-hr">
                                <p class="order-cart-description" id="preview_from"></p>
                                <p class="order-cart-description" id="preview_to"></p>
                            </div>
                            <div class="order-cart">
                                <p class="order-cart-header">Груз</p>
                                <hr class="order-hr">
                                <p class="order-cart-description" id="preview_cargo"></p>
                            </div>
                            <div class="order-cart">
                                <p class="order-cart-header">Транспорт</p>
                                <hr class="order-hr">
                                <p class="order-cart-description" id="preview_vehicle"></p>
                            </div>
                            <div class="order-cart">
                                <p class="order-cart-header">Услуги</p>
                                <hr class="order-hr">
                                <p class="order-cart-description" id="preview_services"></p>
                            </div>
                            <div class="order-cart end">
                                <p>Стоимость</p>
                                <hr class="order-hr">
                                <p id="preview_total">0 ₽</p>
                            </div>

                            <div class="contact-information">
                                <p class="address-header">Контактная информация</p>
                                <div class="filling-cart">
                                    <input type="text" name="contact_person" placeholder="Контактное лицо*" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                    <input type="text" name="contact_phone" placeholder="Телефон*" value="<?php echo htmlspecialchars($phone); ?>" required>
                                    <input type="email" name="contact_email" placeholder="Email*" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <input type="date" name="desired_date" id="desired_date">
                                </div>
                                <h3 class="cargo-text">Особые указания</h3>
                                <textarea class="cargo-textarea" name="special_instructions" placeholder="Дополнительные пожелания"></textarea>
                            </div>

                            <div class="contact-information-checkbox">
                                <input type="checkbox" name="agree_terms" id="agree_terms" required>
                                <p>Я согласен с <a href="#">условиями перевозки</a> и <a href="#">политикой конфиденциальности</a>*</p>
                            </div>

                            <div class="new_order_a">
                                <button type="button" class="transition back" onclick="prevStep(3)">Назад</button>
                                <button type="submit" class="transition last" name="create_order">Оформить заказ</button>
                            </div>
                        </div>
                        <hr class="cargo-hr">
                    </div>
                    <div class="new_order_a">
                        <a href="" class="transition back" data-tab="service"> Назад</a>
                        <a href="" class="transition last" data-tab="order"> Оформление заказа</a>
                    </div>
                </div>
                </form>
            </div>

        <div class="tab-content" id="history">
            <h1 class="account-main-header">История заказов</h1>
            <?php if ($all_orders_result->num_rows > 0): ?>
                <table class="iksweb">
                    <thead>
                        <tr class="history-table-header">
                            <td>Номер заказа</td>
                            <td>Дата</td>
                            <td>Маршрут</td>
                            <td>Груз</td>
                            <td>Стоимость</td>
                            <td>Статус</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $all_orders_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo date('d.m.Y', strtotime($order['create_date'])); ?></td>
                                <td><?php echo $order['from_address']; ?> → <?php echo $order['to_address']; ?></td>
                                <td><?php echo $order['cargo_type'] ?: 'Не указано'; ?></td>
                                <td><?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽</td>
                                <td><?php echo $order['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; padding: 2rem; color: #666;">Заказов пока нет</p>
            <?php endif; ?>
        </div>

        <div class="tab-content" id="payment">
            <h1 class="account-main-header">Оплата и счета</h1>

            <h2 class="account-table-header">Способы оплаты</h2>
            <div class="account-payment-cart">
                <div class="payment-cart">
                    <p><i class="fa-solid fa-credit-card"></i> Банковская карта</p>
                    <p>Добавьте карту для быстрой оплаты</p>
                    <button onclick="showCardForm()">Добавить карту</button>
                </div>
                <div class="payment-cart">
                    <p><i class="fa-solid fa-building-columns"></i> Банковский перевод</p>
                    <p>Реквизиты для перевода</p>
                    <button onclick="showBankDetails()">Показать реквизиты</button>
                </div>
            </div>

            <h2 class="account-table-header">История платежей</h2>
            <?php if ($payments_result->num_rows > 0): ?>
                <?php while($payment = $payments_result->fetch_assoc()): ?>
                    <div class="account-history-cart">
                        <div>
                            <p class="account-payment-header">Оплата заказа №<?php echo $payment['order_id']; ?></p>
                            <p class="account-payment-inscription"><?php echo date('d.m.Y', strtotime($payment['create_date'])); ?></p>
                        </div>
                        <div class="account-payment">
                            <p class="account-payment-header"><?php echo number_format($payment['total_price'], 0, '', ' '); ?> ₽</p>
                            <p class="account-payment-inscription-p">Оплачено</p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Платежей пока нет</p>
            <?php endif; ?>
        </div>

        <div class="tab-content" id="profile">
            <h1 class="account-main-header">Профиль</h1>
            <div class="setting">
                <h2 class="profile-header">Настройки профиля</h2>
                <form action="profile_handler.php" method="POST" id="profileForm">
                    <div class="profile-main">
                        <div>
                            <label for="full_name">ФИО</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                            <label for="profile_email">Email</label>
                            <input type="email" id="profile_email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div>
                            <label for="profile_phone">Телефон</label>
                            <input type="text" id="profile_phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                            <label for="company">Компания</label>
                            <input type="text" id="company" name="company">
                        </div>
                    </div>
                    <h3 class="profile-header-text">Адрес</h3>
                    <textarea class="profile-header-textarea" name="address" id="address" placeholder="Введите ваш адрес"></textarea>
                    <button class="profile-button" type="submit" name="update_profile">Сохранить изменения</button>
                </form>
            </div>
        </div>

        <div class="tab-content" id="settings">
            <h1 class="account-main-header">Настройки</h1>
            <h2 class="setting-inscription">Настройки аккаунта</h2>
            
            <form action="settings_handler.php" method="POST" id="settingsForm">
                <div class="setting-table-cart">
                    <div>
                        <p class="setting-table-header">Уведомления по email</p>
                        <p class="setting-table-inscription">Получать уведомления о статусе заказов</p>
                    </div>
                    <div class="switch-button">
                        <label class="switch">
                            <input type="checkbox" name="email_notifications" checked>
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
                            <input type="checkbox" name="sms_notifications" checked>
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
                        <select name="language" id="language">
                            <option value="ru">Русский</option>
                            <option value="en">Английский</option>
                        </select>
                    </div>
                </div>
                
                <div class="new_order_a" style="margin-top: 20px;">
                    <button type="submit" class="profile-button">Сохранить настройки</button>
                </div>
            </form>

            <div class="setting-table-footer">
                <p class="setting-table-footer-inscription">Опасная зона</p>
                <div>
                    <button class="setting-table-footer-b1" onclick="confirmDeleteAccount()">Удалить аккаунт</button>
                    <button class="setting-table-footer-b2" onclick="exportData()">Экспорт данных</button>
                </div>
            </div>
        </div>

    </div>
</section>