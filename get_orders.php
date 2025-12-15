<?php
session_start();
require_once('db.php');

if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.*, v.name as vehicle_name FROM orders o LEFT JOIN vehicles v ON o.vehicle_id = v.id WHERE o.client_id = '$user_id' ORDER BY o.create_date DESC";

$result = $conn->query($sql);
$orders = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services_sql = "SELECT s.name FROM order_services os 
                LEFT JOIN services s ON os.service_id = s.id 
                WHERE os.order_id = '{$row['id']}'";
        $services_result = $conn->query($services_sql);
        $services = [];
        while($service = $services_result->fetch_assoc()) {
            $services[] = $service['name'];
        }
        $row['services'] = $services;
        $orders[] = $row;
    }
}
header('Content-Type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
echo json_encode($orders);
?>