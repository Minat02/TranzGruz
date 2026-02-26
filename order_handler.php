[file name]: order_handler.php
[file content begin]
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_log("=== ORDER_HANDLER START ===");
error_log("POST данные: " . print_r($_POST, true));
error_log("Сессия: " . print_r($_SESSION, true));

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    error_log("Пользователь не авторизован");
    $_SESSION['error'] = "Для создания заказа необходимо авторизоваться";
    header("Location: index.php");
    exit();
}

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_order'])) {
    $user_id = $_SESSION['user_id'] ?? 0;

    $sql_check = "SELECT id, username FROM users WHERE id = '$user_id' AND id IS NOT NULL";
    $result_check = $conn->query($sql_check);
    
    if (!$result_check) {
        error_log("Ошибка проверки пользователя: " . $conn->error);
        $_SESSION['error'] = "Ошибка проверки данных пользователя";
        header("Location: account.php?tab=new_order");
        exit();
    }
    
    if ($result_check->num_rows == 0) {
        error_log("Пользователь не найден: ID=$user_id");
        error_log("Данные сессии: user_id=" . ($_SESSION['user_id'] ?? 'не установлен'));

        session_destroy();
        $_SESSION['error'] = "Сессия устарела. Пожалуйста, войдите снова.";
        header("Location: index.php");
        exit();
    }

    $user_data = $result_check->fetch_assoc();
    error_log("Пользователь найден: " . $user_data['username'] . " (ID: " . $user_data['id'] . ")");

    $required_fields = [
        'from_country', 'from_city', 'from_address',
        'to_country', 'to_city', 'to_address',
        'cargo_type', 'cargo_weight', 'cargo_description',
        'vehicle_id', 'contact_person', 'contact_phone', 'contact_email'
    ];
    
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        error_log("Отсутствуют обязательные поля: " . implode(', ', $missing_fields));
        $_SESSION['error'] = "Заполните все обязательные поля";
        header("Location: account.php?tab=new_order");
        exit();
    }

    $vehicle_id = $conn->real_escape_string($_POST['vehicle_id']);
    $vehicle_name = $conn->real_escape_string($_POST['vehicle_name'] ?? '');
    $vehicle_price = $conn->real_escape_string($_POST['vehicle_price'] ?? 0);

    $vehicle_check = $conn->query("SELECT id FROM vehicles WHERE id = '$vehicle_id'");
    if (!$vehicle_check || $vehicle_check->num_rows == 0) {
        $_SESSION['error'] = "Выбранный транспорт не существует";
        header("Location: account.php?tab=new_order");
        exit();
    }
    
    $from_address = $conn->real_escape_string(
        ($_POST['from_country'] ?? '') . ', ' .
        ($_POST['from_city'] ?? '') . ', ' .
        ($_POST['from_address'] ?? '')
    );
    
    $to_address = $conn->real_escape_string(
        ($_POST['to_country'] ?? '') . ', ' .
        ($_POST['to_city'] ?? '') . ', ' .
        ($_POST['to_address'] ?? '')
    );
    
    $cargo_type = $conn->real_escape_string($_POST['cargo_type'] ?? '');
    $cargo_weight = floatval($_POST['cargo_weight'] ?? 0);
    $cargo_description = $conn->real_escape_string($_POST['cargo_description'] ?? '');
    $cargo_volume = $conn->real_escape_string($_POST['cargo_volume'] ?? '');
    $cargo_value = floatval($_POST['cargo_value'] ?? 0);
    $is_fragile = isset($_POST['is_fragile']) ? 1 : 0;
    
    $contact_person = $conn->real_escape_string($_POST['contact_person'] ?? '');
    $contact_phone = $conn->real_escape_string($_POST['contact_phone'] ?? '');
    $contact_email = $conn->real_escape_string($_POST['contact_email'] ?? '');
    $desired_date = $conn->real_escape_string($_POST['desired_date'] ?? '');
    $special_instructions = $conn->real_escape_string($_POST['special_instructions'] ?? '');
    $services = $_POST['services'] ?? [];
    $services_price = 0;
    $vehicle_price_num = floatval($vehicle_price);
    $total_price = $vehicle_price_num;
    if (!empty($services)) {
        $valid_services = [];
        foreach ($services as $service_id) {
            $service_id_clean = intval($service_id);
            if ($service_id_clean > 0) {
                $valid_services[] = $service_id_clean;
            }
        }
        
        if (!empty($valid_services)) {
            $services_list = implode(',', $valid_services);
            $services_sql = "SELECT SUM(price) as total FROM services WHERE id IN ($services_list)";
            $services_result = $conn->query($services_sql);
            
            if ($services_result && $row = $services_result->fetch_assoc()) {
                $services_price = floatval($row['total'] ?? 0);
                $total_price += $services_price;
            }
        }
    }
    $sql = "INSERT INTO orders (
        client_id, 
        vehicle_id, 
        status, 
        from_address, 
        to_address, 
        total_price, 
        create_date,
        cargo_type,
        cargo_weight,
        cargo_description,
        cargo_volume,
        cargo_value,
        contact_person,
        contact_phone,
        notes
    ) VALUES (
        '$user_id',
        '$vehicle_id',
        'В обработке',
        '$from_address',
        '$to_address',
        '$total_price',
        CURDATE(),
        '$cargo_type',
        '$cargo_weight',
        '$cargo_description',
        '$cargo_volume',
        '$cargo_value',
        '$contact_person',
        '$contact_phone',
        '$special_instructions'
    )";
    
    error_log("SQL запрос создания заказа: " . $sql);
    
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        error_log("Заказ создан, ID: " . $order_id);
        if (!empty($valid_services)) {
            foreach ($valid_services as $service_id) {
                $service_sql = "INSERT INTO order_services (order_id, service_id) VALUES ('$order_id', '$service_id')";
                if ($conn->query($service_sql) !== TRUE) {
                    error_log("Ошибка добавления услуги: " . $conn->error);
                }
            }
        }
        
        $_SESSION['success'] = "Заказ #$order_id успешно создан!";
        header("Location: account.php?tab=orders");
        exit();
        
    } else {
        error_log("Ошибка SQL: " . $conn->error);
        if (strpos($conn->error, 'foreign key constraint') !== false) {
            $_SESSION['error'] = "Ошибка данных. Пожалуйста, войдите в систему заново и попробуйте снова.";
            session_destroy();
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Ошибка при создании заказа: " . $conn->error;
            header("Location: account.php?tab=new_order");
            exit();
        }
    }
    
} else {
    error_log("Неправильный запрос: метод=" . $_SERVER['REQUEST_METHOD'] . ", create_order=" . ($_POST['create_order'] ?? 'не установлен'));
    $_SESSION['error'] = "Неправильный запрос";
    header("Location: account.php");
    exit();
}
?>
[file content end]