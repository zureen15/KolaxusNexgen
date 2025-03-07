<?php
// Include config file
require_once "../database/config.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Start session
session_start();

// Assume $user_id is the ID of the currently logged-in user
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id']; // Adjust this based on your session management

// Check if the user is an admin
// if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
//     echo "Admins cannot redeem vouchers.";
//     exit();
// }

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['voucher_id'])) {
        echo "Voucher ID not provided.";
        exit();
    }

    $voucher_id = $_POST['voucher_id']; // Adjust this based on your form data

    // Check if the user has already claimed this voucher
    $sql = "SELECT COUNT(*) as claim_count FROM user_vouchers WHERE user_id = ? AND voucher_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $voucher_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $claim_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($claim_count > 0) {
            echo "You have already claimed this voucher.";
            exit();
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    // Check the total number of claims for this voucher
    $sql = "SELECT COUNT(*) as total_claims, max_claims FROM loyalty_program lp LEFT JOIN user_vouchers uv ON lp.voucher_id = uv.voucher_id WHERE lp.voucher_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $voucher_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $total_claims, $max_claims);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($total_claims >= $max_claims) {
            echo "This voucher has already been claimed by the maximum number of users.";
            exit();
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    // Insert the redemption record
    $sql = "INSERT INTO user_vouchers (user_id, voucher_id) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $voucher_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Voucher redeemed successfully.";
        } else {
            echo "Error executing statement: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

// Close connection
mysqli_close($conn);
?>