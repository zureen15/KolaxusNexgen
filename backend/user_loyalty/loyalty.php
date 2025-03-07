<?php
session_start();
require '../database/config.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$loyalty_type = '';
if (isset($_GET['loyalty_type'])) {
    $loyalty_type = $_GET['loyalty_type'];
}

$sql = "SELECT lp.*, COUNT(uv.id) as claims FROM loyalty_program lp LEFT JOIN user_vouchers uv ON lp.voucher_id = uv.voucher_id";
if (!empty($loyalty_type)) {
    $sql .= " WHERE lp.loyalty_type = ?";
}
$sql .= " GROUP BY lp.voucher_id";

$stmt = $conn->prepare($sql);
if (!empty($loyalty_type)) {
    $stmt->bind_param("s", $loyalty_type);
}
$stmt->execute();
$result = $stmt->get_result();

?>