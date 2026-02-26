<?php
function getInitials($name)
{
    $names = explode(' ', $name);
    $initials = '';
    foreach ($names as $n) {
        $initials .= strtoupper(substr($n, 0, 1));
    }
    return $initials ?: 'U';
}

function checkAuth() {
    if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        header("Location: index.php");
        exit();
    }
}

function getUserData($conn, $user_id) {
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}
?>