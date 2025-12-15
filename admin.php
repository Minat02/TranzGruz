<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("include/header.php");

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$initials = getInitials($username);
require_once('db.php');
$orders_sql = "SELECT o.*, u.username, u.full_name FROM orders o 
               LEFT JOIN users u ON o.client_id = u.id 
               ORDER BY o.create_date DESC LIMIT 50";
$orders_result = $conn->query($orders_sql);
$orders = [];
while($order = $orders_result->fetch_assoc()) {
    $orders[] = $order;
}

$users_sql = "SELECT id, username, email, phone, full_name, 
                     DATE_FORMAT(created_at, '%d.%m.%Y') as reg_date 
              FROM users ORDER BY created_at DESC";
$users_result = $conn->query($users_sql);
$users = [];
while($user = $users_result->fetch_assoc()) {
    $users[] = $user;
}

$vehicles_sql = "SELECT * FROM vehicles ORDER BY name";
$vehicles_result = $conn->query($vehicles_sql);
$vehicles = [];
while($vehicle = $vehicles_result->fetch_assoc()) {
    $vehicles[] = $vehicle;
}

$services_sql = "SELECT * FROM services ORDER BY name";
$services_result = $conn->query($services_sql);
$services = [];
while($service = $services_result->fetch_assoc()) {
    $services[] = $service;
}
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
                    <p class="account-email"><?php echo htmlspecialchars($username); ?></p>
                </div>
            </div>
            <hr class="order-hr">
            <div class="account-menu">
                <a href="#" class="menu-item active" data-tab="orders"><i class="fa-solid fa-boxes-stacked"></i> Управление заказами</a>
                <a href="#" class="menu-item" data-tab="users"><i class="fa-solid fa-users"></i> Управление пользователями</a>
                <a href="#" class="menu-item" data-tab="transport"><i class="fa-solid fa-truck"></i> Управление транспортом</a>
                <a href="#" class="menu-item" data-tab="services"><i class="fa-solid fa-gears"></i> Управление услугами</a>
                <a href="logout.php" class="menu-item exit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Выйти</a>
            </div>
        </div>
    </aside>
    <div class="account-main">
        <div class="tab-content active" id="orders">
            <h1 class="account-main-header">Управление заказами</h1>
                        <div class="admin-header-grid">
                <p>Статус:</p>
                <p>Дата от:</p>
                <p>Дата до:</p>
                <select id="pet-select">
                    <option value="All-statuses">Все статусы</option>
                    <option value="В обработке">В обработке</option>
                    <option value="В пути">В пути</option>
                    <option value="Доставлен">Доставлен</option>
                    <option value="Отменен">Отменен</option>
                </select>
                <input id="date-from" type="date" />
                <input id="date-to" type="date" />
                <div class="admin-header-a">
                    <button class="apply">Применить</button>
                    <button class="reset">Сбросить</button>
                </div>
            </div>
            <table class="orders">
                <thead>
                    <tr>
                        <td class="td-header">ID заказа</td>
                        <td class="td-header">Клиент</td>
                        <td class="td-header">Маршрут</td>
                        <td class="td-header">Статус</td>
                        <td class="td-header">Стоимость</td>
                        <td class="td-header">Действие</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['full_name'] ?? $order['username']); ?></td>
                        <td><?php echo htmlspecialchars($order['from_address']); ?> → <?php echo htmlspecialchars($order['to_address']); ?></td>
                        <td class="status">
                            <span class="<?php 
                                switch($order['status']) {
                                    case 'В пути': echo 'On-the-way'; break;
                                    case 'Доставлен': echo 'Delivered'; break;
                                    default: echo 'Waiting';
                                }
                            ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽</td>
                        <td class="actions">
                            <i class="fa-solid fa-pen-to-square" data-id="<?php echo $order['id']; ?>"></i>
                            <i class="fa-solid fa-trash" data-id="<?php echo $order['id']; ?>"></i>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="tab-content" id="users">
            <h1 class="account-main-header">Управление пользователями</h1>
            <table class="orders">
                <thead>
                    <tr>
                        <td class="td-header">ID</td>
                        <td class="td-header">Имя пользователя</td>
                        <td class="td-header">Email</td>
                        <td class="td-header">Телефон</td>
                        <td class="td-header">Дата регистрации</td>
                        <td class="td-header">Действие</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone'] ?? 'Не указан'); ?></td>
                        <td><?php echo htmlspecialchars($user['reg_date'] ?? 'Нет данных'); ?></td>
                        <td class="actions">
                            <i class="fa-solid fa-pen-to-square" data-id="<?php echo $user['id']; ?>"></i>
                            <i class="fa-solid fa-trash" data-id="<?php echo $user['id']; ?>"></i>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="tab-content" id="transport">
            <h1 class="account-main-header">Управление транспортом</h1>
            <div class="transport-cars-cart">
                <?php foreach($vehicles as $vehicle): ?>
                <div class="cars-cart">
                    <i class="fa-solid fa-truck"></i>
                    <p class="cars-cart-name"><?php echo htmlspecialchars($vehicle['name']); ?></p>
                    <p class="cars-cart-kg"><?php echo htmlspecialchars($vehicle['capacity']); ?></p>
                    <p class="cars-cart-price"><?php echo number_format($vehicle['price'], 0, '', ' '); ?> ₽</p>
                    <p class="<?php echo ($vehicle['status'] ?? 'available') == 'available' ? 'cars-cart-status-available' : 'cars-cart-status-busy'; ?>">
                        <?php echo ($vehicle['status'] ?? 'available') == 'available' ? 'Доступен' : 'Занят'; ?>
                    </p>
                    <div class="cars-cart-actions">
                        <i class="fa-solid fa-pen-to-square edit-vehicle" data-id="<?php echo $vehicle['id']; ?>"></i>
                        <i class="fa-solid fa-trash delete-vehicle" data-id="<?php echo $vehicle['id']; ?>"></i>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="cars-cart-add">
                    <i class="fa-solid fa-plus"></i>
                    <p class="cars-cart-add-add">Добавить</p>
                    <p class="cars-cart-add-new">новый транспорт</p>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="services">
            <h1 class="account-main-header">Управление услугами</h1>
            <div class="services-list">
                <?php foreach($services as $service): ?>
                <div class="services-cart">
                    <div>
                        <p class="services-header"><?php echo htmlspecialchars($service['name']); ?></p>
                        <p class="services-inscription"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>
                    </div>
                    <div class="services-change">
                        <p class="services-price"><?php echo number_format($service['price'], 0, '', ' '); ?> ₽</p>
                        <i class="fa-solid fa-pen-to-square edit-service" data-id="<?php echo $service['id']; ?>"></i>
                        <i class="fa-solid fa-trash delete-service" data-id="<?php echo $service['id']; ?>"></i>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="services-cart-add">
                    <i class="fa-solid fa-plus"></i>
                    <p class="services-cart-add-new">Добавить новую услугу</p>
                </div>
            </div>
        </div>
    </div>
<!-- Модальное окно для редактирования заказа -->
<div id="orderModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Редактирование заказа</h3>
            <span class="admin-modal-close" onclick="closeModal('orderModal')">&times;</span>
        </div>
        <form id="orderForm" onsubmit="saveOrder(event)">
            <input type="hidden" id="order_id" name="order_id">
            <div class="admin-form-group">
                <label for="order_status">Статус</label>
                <select id="order_status" name="status" required>
                    <option value="В обработке">В обработке</option>
                    <option value="В пути">В пути</option>
                    <option value="Доставлен">Доставлен</option>
                    <option value="Отменен">Отменен</option>
                </select>
            </div>
            <div class="admin-form-group">
                <label for="order_comment">Комментарий</label>
                <textarea id="order_comment" name="comment" rows="3"></textarea>
            </div>
            <div class="admin-form-actions">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeModal('orderModal')">Отмена</button>
                <button type="submit" class="admin-btn admin-btn-primary">Сохранить</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="deleteOrder()">Удалить</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно для редактирования пользователя -->
<div id="userModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Редактирование пользователя</h3>
            <span class="admin-modal-close" onclick="closeModal('userModal')">&times;</span>
        </div>
        <form id="userForm" onsubmit="saveUser(event)">
            <input type="hidden" id="user_id" name="user_id">
            <div class="admin-form-group">
                <label for="user_username">Имя пользователя</label>
                <input type="text" id="user_username" name="username" required>
            </div>
            <div class="admin-form-group">
                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="email" required>
            </div>
            <div class="admin-form-group">
                <label for="user_phone">Телефон</label>
                <input type="text" id="user_phone" name="phone">
            </div>
            <div class="admin-form-group">
                <label for="user_full_name">Полное имя</label>
                <input type="text" id="user_full_name" name="full_name">
            </div>
            <div class="admin-form-group">
                <label for="user_role">Роль</label>
                <select id="user_role" name="role">
                    <option value="user">Пользователь</option>
                    <option value="admin">Администратор</option>
                </select>
            </div>
            <div class="admin-form-actions">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeModal('userModal')">Отмена</button>
                <button type="submit" class="admin-btn admin-btn-primary">Сохранить</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="deleteUser()">Удалить</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно для редактирования транспорта -->
<div id="vehicleModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3><?php echo isset($_GET['edit_vehicle']) ? 'Редактирование транспорта' : 'Добавление транспорта'; ?></h3>
            <span class="admin-modal-close" onclick="closeModal('vehicleModal')">&times;</span>
        </div>
        <form id="vehicleForm" onsubmit="saveVehicle(event)">
            <input type="hidden" id="vehicle_id" name="vehicle_id">
            <div class="admin-form-group">
                <label for="vehicle_name">Название</label>
                <input type="text" id="vehicle_name" name="name" required>
            </div>
            <div class="admin-form-group">
                <label for="vehicle_capacity">Грузоподъемность (кг)</label>
                <input type="number" id="vehicle_capacity" name="capacity" required>
            </div>
            <div class="admin-form-group">
                <label for="vehicle_price">Цена (₽)</label>
                <input type="number" id="vehicle_price" name="price" required>
            </div>
            <div class="admin-form-group">
                <label for="vehicle_status">Статус</label>
                <select id="vehicle_status" name="status">
                    <option value="available">Доступен</option>
                    <option value="busy">Занят</option>
                </select>
            </div>
            <div class="admin-form-actions">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeModal('vehicleModal')">Отмена</button>
                <button type="submit" class="admin-btn admin-btn-primary">Сохранить</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="deleteVehicle()" style="display: none;" id="deleteVehicleBtn">Удалить</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно для редактирования услуги -->
<div id="serviceModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3><?php echo isset($_GET['edit_service']) ? 'Редактирование услуги' : 'Добавление услуги'; ?></h3>
            <span class="admin-modal-close" onclick="closeModal('serviceModal')">&times;</span>
        </div>
        <form id="serviceForm" onsubmit="saveService(event)">
            <input type="hidden" id="service_id" name="service_id">
            <div class="admin-form-group">
                <label for="service_name">Название услуги</label>
                <input type="text" id="service_name" name="name" required>
            </div>
            <div class="admin-form-group">
                <label for="service_description">Описание</label>
                <textarea id="service_description" name="description" rows="3" required></textarea>
            </div>
            <div class="admin-form-group">
                <label for="service_price">Цена (₽)</label>
                <input type="number" id="service_price" name="price" required>
            </div>
            <div class="admin-form-actions">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeModal('serviceModal')">Отмена</button>
                <button type="submit" class="admin-btn admin-btn-primary">Сохранить</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="deleteService()" style="display: none;" id="deleteServiceBtn">Удалить</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/admin.js"></script>
</section>