<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_order'])) {
    $client_id = $_SESSION['user_id'];
    $vehicle_id = $conn->real_escape_string($_POST['vehicle_id']);
    $from_country = $conn->real_escape_string($_POST['from_country']);
    $from_city = $conn->real_escape_string($_POST['from_city']);
    $from_address = $conn->real_escape_string($_POST['from_address']);
    $from_postal = $conn->real_escape_string($_POST['from_postal'] ?? '');
    
    $to_country = $conn->real_escape_string($_POST['to_country']);
    $to_city = $conn->real_escape_string($_POST['to_city']);
    $to_address = $conn->real_escape_string($_POST['to_address']);
    $to_postal = $conn->real_escape_string($_POST['to_postal'] ?? '');
    
    $cargo_type = $conn->real_escape_string($_POST['cargo_type']);
    $cargo_weight = $conn->real_escape_string($_POST['cargo_weight']);
    $cargo_volume = $conn->real_escape_string($_POST['cargo_volume'] ?? '');
    $cargo_value = $conn->real_escape_string($_POST['cargo_value'] ?? '');
    $cargo_description = $conn->real_escape_string($_POST['cargo_description'] ?? '');
    $is_fragile = isset($_POST['is_fragile']) ? 1 : 0;
    
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    $contact_phone = $conn->real_escape_string($_POST['contact_phone']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $desired_date = $conn->real_escape_string($_POST['desired_date'] ?? '');
    $special_instructions = $conn->real_escape_string($_POST['special_instructions'] ?? '');
    $total_price = $conn->real_escape_string($_POST['total_price']);

    $from_full = "$from_country, $from_city, $from_address" . ($from_postal ? ", $from_postal" : "");
    $to_full = "$to_country, $to_city, $to_address" . ($to_postal ? ", $to_postal" : "");

    $status = 'В обработке';

    $order_sql = "INSERT INTO orders (client_id, vehicle_id, status, from_address, to_address, total_price, create_date, cargo_type, cargo_weight, contact_person, contact_phone) 
                  VALUES ('$client_id', '$vehicle_id', '$status', '$from_full', '$to_full', '$total_price', CURDATE(), '$cargo_type', '$cargo_weight', '$contact_person', '$contact_phone')";
    
    if ($conn->query($order_sql) === TRUE) {
        $order_id = $conn->insert_id;

        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $service_id) {
                $service_id = $conn->real_escape_string($service_id);
                $service_sql = "INSERT INTO order_services (order_id, service_id) VALUES ('$order_id', '$service_id')";
                $conn->query($service_sql);
            }
        }
        
        $_SESSION['success'] = "Заказ успешно создан! Номер заказа: #" . $order_id;
        header("Location: account.php");
        exit();
    } else {
        $_SESSION['error'] = "Ошибка при создании заказа: " . $conn->error;
        header("Location: account.php");
        exit();
    }
} else {
    header("Location: account.php");
    exit();
}
?>