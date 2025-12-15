<?php
session_start();
require_once('db.php');
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || ($_SESSION['role'] ?? 'user') != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Доступ запрещен']);
    exit();
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? null;

switch($type) {
    case 'orders':
        getOrders($conn);
        break;
    case 'order':
        if ($id) {
            getOrder($conn, $id);
        } else {
            echo json_encode(['error' => 'ID заказа не указан']);
        }
        break;
    case 'users':
        if ($id) {
            getUser($conn, $id);
        } else {
            getUsers($conn);
        }
        break;
    case 'vehicles':
        if ($id) {
            getVehicle($conn, $id);
        } else {
            getVehicles($conn);
        }
        break;
    case 'services':
        if ($id) {
            getService($conn, $id);
        } else {
            getServices($conn);
        }
        break;
    case 'user':
        getUser($conn, $id);
        break;
    case 'vehicle':
        getVehicle($conn, $id);
        break;
    case 'service':
        getService($conn, $id);
        break;
    default:
        echo json_encode(['error' => 'Неверный тип запроса']);
}

function getOrder($conn, $id) {
    $id = $conn->real_escape_string($id);
    $table_check = $conn->query("SHOW TABLES LIKE 'status_history'");
    $has_status_history = ($table_check && $table_check->num_rows > 0);
    
    $sql = "SELECT o.*, 
                   u.username, 
                   u.full_name, 
                   u.phone as client_phone, 
                   v.name as vehicle_name, 
                   v.capacity";
    if ($has_status_history) {
        $sql .= ", (SELECT comment FROM status_history WHERE order_id = o.id ORDER BY create_date DESC LIMIT 1) as comment";
    } else {
        $sql .= ", '' as comment";
    }
    
    $sql .= " FROM orders o
            LEFT JOIN users u ON o.client_id = u.id
            LEFT JOIN vehicles v ON o.vehicle_id = v.id
            WHERE o.id = '$id'";
    
    $result = $conn->query($sql);
    $order = null;
    
    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $services_sql = "SELECT s.name FROM order_services os 
                        LEFT JOIN services s ON os.service_id = s.id 
                        WHERE os.order_id = '$id'";
        $services_result = $conn->query($services_sql);
        $services = [];
        if ($services_result) {
            while($service = $services_result->fetch_assoc()) {
                $services[] = $service['name'];
            }
        }
        $order['services'] = $services;
    } else {
        $order = ['error' => 'Заказ не найден'];
    }
    
    echo json_encode($order);
    exit();
}

function getOrders($conn) {
    $status = $_GET['status'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    
    $sql = "SELECT o.*, u.username, u.full_name, u.phone as client_phone, 
                   v.name as vehicle_name, v.capacity
            FROM orders o
            LEFT JOIN users u ON o.client_id = u.id
            LEFT JOIN vehicles v ON o.vehicle_id = v.id
            WHERE 1=1";
    
    if ($status && $status != 'All-statuses') {
        $status = $conn->real_escape_string($status);
        $sql .= " AND o.status = '$status'";
    }
    
    if ($date_from) {
        $date_from = $conn->real_escape_string($date_from);
        $sql .= " AND o.create_date >= '$date_from'";
    }
    
    if ($date_to) {
        $date_to = $conn->real_escape_string($date_to);
        $sql .= " AND o.create_date <= '$date_to'";
    }
    
    $sql .= " ORDER BY o.create_date DESC";
    
    $result = $conn->query($sql);
    $orders = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $services_sql = "SELECT s.name FROM order_services os 
                            LEFT JOIN services s ON os.service_id = s.id 
                            WHERE os.order_id = '{$row['id']}'";
            $services_result = $conn->query($services_sql);
            $services = [];
            if ($services_result) {
                while($service = $services_result->fetch_assoc()) {
                    $services[] = $service['name'];
                }
            }
            $row['services'] = $services;
            $orders[] = $row;
        }
    }
    
    echo json_encode($orders);
    exit();
}

function getUsers($conn) {
    $sql = "SELECT id, username, email, phone, full_name, role, 
                   DATE_FORMAT(created_at, '%d.%m.%Y') as registration_date
            FROM users 
            ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    $users = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    
    echo json_encode($users);
    exit();
}

function getVehicles($conn) {
    $sql = "SELECT * FROM vehicles ORDER BY name";
    
    $result = $conn->query($sql);
    $vehicles = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
    }
    
    echo json_encode($vehicles);
    exit();
}

function getServices($conn) {
    $sql = "SELECT * FROM services WHERE is_active = 1 ORDER BY name";

    $result = $conn->query($sql);
    $services = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }

    echo json_encode($services);
    exit();
}

function getUser($conn, $id) {
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM users WHERE id = '$id'";

    $result = $conn->query($sql);
    $user = null;

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    echo json_encode($user);
    exit();
}

function getVehicle($conn, $id) {
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM vehicles WHERE id = '$id'";

    $result = $conn->query($sql);
    $vehicle = null;

    if ($result && $result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    }

    echo json_encode($vehicle);
    exit();
}

function getService($conn, $id) {
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM services WHERE id = '$id'";

    $result = $conn->query($sql);
    $service = null;

    if ($result && $result->num_rows > 0) {
        $service = $result->fetch_assoc();
    }

    echo json_encode($service);
    exit();
}