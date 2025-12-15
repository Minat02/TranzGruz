<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Для отладки
error_log("=== ADMIN_HANDLER START ===");
error_log("POST данные: " . print_r($_POST, true));
error_log("Сессия: " . print_r($_SESSION, true));

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || ($_SESSION['role'] ?? 'user') != 'admin') {
    error_log("Доступ запрещен: не авторизован или не админ");
    header("Location: index.php");
    exit();
}

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    error_log("Действие: " . $action);
    
    switch($action) {
        case 'update_order_status':
            updateOrderStatus($conn, $_POST);
            break;
        case 'delete_order':
            deleteOrder($conn, $_POST['order_id']);
            break;
        case 'update_user':
            updateUser($conn, $_POST);
            break;
        case 'delete_user':
            deleteUser($conn, $_POST['user_id']);
            break;
        case 'add_vehicle':
            addVehicle($conn, $_POST);
            break;
        case 'update_vehicle':
            updateVehicle($conn, $_POST);
            break;
        case 'delete_vehicle':
            deleteVehicle($conn, $_POST['vehicle_id']);
            break;
        case 'add_service':
            addService($conn, $_POST);
            break;
        case 'update_service':
            updateService($conn, $_POST);
            break;
        case 'delete_service':
            deleteService($conn, $_POST['service_id']);
            break;
        default:
            error_log("Неизвестное действие: " . $action);
            $_SESSION['error'] = "Неизвестное действие";
            header("Location: admin.php");
            exit();
    }
} else {
    error_log("Неправильный метод запроса: " . $_SERVER['REQUEST_METHOD']);
    header("Location: admin.php");
    exit();
}

function updateOrderStatus($conn, $data) {
    error_log("updateOrderStatus вызвана с данными: " . print_r($data, true));
    
    $order_id = $conn->real_escape_string($data['order_id'] ?? '');
    $status = $conn->real_escape_string($data['status'] ?? 'В обработке');
    $comment = $conn->real_escape_string($data['comment'] ?? '');

    if (empty($order_id)) {
        $_SESSION['error'] = "ID заказа не указан";
        header("Location: admin.php?tab=orders");
        exit();
    }

    $sql = "UPDATE orders SET status ='$status', updated_at = NOW() WHERE id = '$order_id'";
    error_log("SQL запрос: " . $sql);

    if ($conn->query($sql) === TRUE) {
        $table_check = $conn->query("SHOW TABLES LIKE 'status_history'");
        if ($table_check && $table_check->num_rows > 0) {
            $history_sql = "INSERT INTO status_history (order_id, status, comment, create_date) VALUES ('$order_id', '$status', '$comment', CURDATE())";
            $conn->query($history_sql);
        }
        $_SESSION['success'] = "Статус заказа обновлен!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=orders");
    exit();
}

function deleteOrder($conn, $order_id) {
    error_log("deleteOrder вызвана с order_id: " . $order_id);
    
    $order_id = $conn->real_escape_string($order_id);
    
    if (empty($order_id)) {
        $_SESSION['error'] = "ID заказа не указан";
        header("Location: admin.php?tab=orders");
        exit();
    }
    $conn->query("DELETE FROM order_services WHERE order_id = '$order_id'");
    $table_check = $conn->query("SHOW TABLES LIKE 'status_history'");
    if ($table_check && $table_check->num_rows > 0) {
        $conn->query("DELETE FROM status_history WHERE order_id = '$order_id'");
    }

    $sql = "DELETE FROM orders WHERE id = '$order_id'";
    error_log("SQL запрос удаления: " . $sql);

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Заказ удален!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=orders");
    exit();
}

function updateUser($conn, $data) {
    error_log("updateUser вызвана с данными: " . print_r($data, true));
    
    $user_id = $conn->real_escape_string($data['user_id'] ?? '');
    $username = $conn->real_escape_string($data['username'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $role = $conn->real_escape_string($data['role'] ?? 'user');

    if (empty($user_id)) {
        $_SESSION['error'] = "ID пользователя не указан";
        header("Location: admin.php?tab=users");
        exit();
    }

    $sql = "UPDATE users SET username = '$username', email = '$email', phone = '$phone', role = '$role' WHERE id = '$user_id'";
    error_log("SQL запрос: " . $sql);

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Данные пользователя обновлены!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }

    header("Location: admin.php?tab=users");
    exit();
}

function deleteUser($conn, $user_id) {
    error_log("deleteUser вызвана с user_id: " . $user_id);
    
    $user_id = $conn->real_escape_string($user_id);
    
    if (empty($user_id)) {
        $_SESSION['error'] = "ID пользователя не указан";
        header("Location: admin.php?tab=users");
        exit();
    }
    $orders_sql = "SELECT id FROM orders WHERE client_id = '$user_id'";
    $result = $conn->query($orders_sql);
    
    if ($result) {
        while($order = $result->fetch_assoc()) {
            $order_id = $order['id'];
            $conn->query("DELETE FROM order_services WHERE order_id = '$order_id'");
            $table_check = $conn->query("SHOW TABLES LIKE 'status_history'");
            if ($table_check && $table_check->num_rows > 0) {
                $conn->query("DELETE FROM status_history WHERE order_id = '$order_id'");
            }
        }
        $conn->query("DELETE FROM orders WHERE client_id = '$user_id'");
    }
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    error_log("SQL запрос удаления пользователя: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Пользователь удален!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=users");
    exit();
}

function addVehicle($conn, $data) {
    error_log("addVehicle вызвана с данными: " . print_r($data, true));
    
    $name = $conn->real_escape_string($data['name'] ?? '');
    $capacity = $conn->real_escape_string($data['capacity'] ?? '0');
    $price = $conn->real_escape_string($data['price'] ?? '0');
    $status = $conn->real_escape_string($data['status'] ?? 'available');
    
    if (empty($name)) {
        $_SESSION['error'] = "Название транспорта не указано";
        header("Location: admin.php?tab=transport");
        exit();
    }
    $sql = "INSERT INTO vehicles (name, capacity, price, status) 
            VALUES ('$name', '$capacity', '$price', '$status')";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Транспорт добавлен!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=transport");
    exit();
}

function updateVehicle($conn, $data) {
    error_log("updateVehicle вызвана с данными: " . print_r($data, true));
    
    $vehicle_id = $conn->real_escape_string($data['vehicle_id'] ?? '');
    $name = $conn->real_escape_string($data['name'] ?? '');
    $capacity = $conn->real_escape_string($data['capacity'] ?? '0');
    $price = $conn->real_escape_string($data['price'] ?? '0');
    $status = $conn->real_escape_string($data['status'] ?? 'available');
    
    if (empty($vehicle_id)) {
        $_SESSION['error'] = "ID транспорта не указан";
        header("Location: admin.php?tab=transport");
        exit();
    }
    $sql = "UPDATE vehicles SET 
            name = '$name', 
            capacity = '$capacity', 
            price = '$price',
            status = '$status'
            WHERE id = '$vehicle_id'";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Транспорт обновлен!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=transport");
    exit();
}
function deleteVehicle($conn, $vehicle_id) {
    error_log("deleteVehicle вызвана с vehicle_id: " . $vehicle_id);
    
    $vehicle_id = $conn->real_escape_string($vehicle_id);
    
    if (empty($vehicle_id)) {
        $_SESSION['error'] = "ID транспорта не указан";
        header("Location: admin.php?tab=transport");
        exit();
    }
    
    $sql = "DELETE FROM vehicles WHERE id = '$vehicle_id'";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Транспорт удален!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=transport");
    exit();
}

function addService($conn, $data) {
    error_log("addService вызвана с данными: " . print_r($data, true));
    
    $name = $conn->real_escape_string($data['name'] ?? '');
    $description = $conn->real_escape_string($data['description'] ?? '');
    $price = $conn->real_escape_string($data['price'] ?? '0');
    
    if (empty($name)) {
        $_SESSION['error'] = "Название услуги не указано";
        header("Location: admin.php?tab=services");
        exit();
    }
    
    $sql = "INSERT INTO services (name, description, price) 
            VALUES ('$name', '$description', '$price')";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Услуга добавлена!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=services");
    exit();
}

function updateService($conn, $data) {
    error_log("updateService вызвана с данными: " . print_r($data, true));
    
    $service_id = $conn->real_escape_string($data['service_id'] ?? '');
    $name = $conn->real_escape_string($data['name'] ?? '');
    $description = $conn->real_escape_string($data['description'] ?? '');
    $price = $conn->real_escape_string($data['price'] ?? '0');
    
    if (empty($service_id)) {
        $_SESSION['error'] = "ID услуги не указан";
        header("Location: admin.php?tab=services");
        exit();
    }
    
    $sql = "UPDATE services SET 
            name = '$name', 
            description = '$description', 
            price = '$price'
            WHERE id = '$service_id'";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Услуга обновлена!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=services");
    exit();
}

function deleteService($conn, $service_id) {
    error_log("deleteService вызвана с service_id: " . $service_id);
    
    $service_id = $conn->real_escape_string($service_id);
    
    if (empty($service_id)) {
        $_SESSION['error'] = "ID услуги не указан";
        header("Location: admin.php?tab=services");
        exit();
    }
    
    $sql = "DELETE FROM services WHERE id = '$service_id'";
    error_log("SQL запрос: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Услуга удалена!";
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        $_SESSION['error'] = "Ошибка: " . $conn->error;
    }
    
    header("Location: admin.php?tab=services");
    exit();
}
?>