<?php

require_once "../database/config.php";

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Ensure the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     echo "User not logged in.";
//     exit();
// }

// Check if the user is an admin
// if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
//     echo "Admins cannot redeem vouchers.";
//     exit();
// }

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET["voucher_id"]) && !empty(trim($_GET["voucher_id"]))) {
    require "../database/config.php";

    $sql = "SELECT lp.*, COUNT(uv.id) as claims 
            FROM loyalty_program lp 
            LEFT JOIN user_vouchers uv ON lp.voucher_id = uv.voucher_id 
            WHERE lp.voucher_id = ? 
            GROUP BY lp.voucher_id";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["voucher_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Sanitize output
                $program_name = htmlspecialchars($row["program_name"]);
                $loyalty_type = htmlspecialchars($row["loyalty_type"]);
                $voucher_code = htmlspecialchars($row["voucher_code"]);
                $description = htmlspecialchars($row["challenge_description"]);
                $start_date = htmlspecialchars($row["start_date"]);
                $expiry_date = htmlspecialchars($row["expiry_date"]);
                $max_claims = (int) $row["max_claims"];
                $image = htmlspecialchars($row["image"]);
                $claims = (int) $row["claims"];
            } else {
                header("location: ../Admin/error_loyalty.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong while retrieving voucher details. Please try again later.";
            exit();
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    // Check if the user has already claimed this voucher
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) as claim_count 
            FROM user_vouchers 
            WHERE user_id = ? AND voucher_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $param_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $claim_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $already_claimed = $claim_count > 0;
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }

    // Close connection
    mysqli_close($conn);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: ../Admin/error_loyalty.php");
    exit();
}
?>