<?php

session_start();
require "../database/config.php";

header('Content-Type: application/json'); // Set response header for JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "You need to log in to join a challenge"
        ]);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $challenge_id = intval($_POST['challenge_id']); // Sanitize challenge_id
    // Check if the user is an admin
    $role_check_sql = "SELECT user_type FROM users WHERE user_id = ?";
    if ($role_check_stmt = mysqli_prepare($conn, $role_check_sql)) {
        mysqli_stmt_bind_param($role_check_stmt, "i", $user_id);
        mysqli_stmt_execute($role_check_stmt);
        mysqli_stmt_bind_result($role_check_stmt, $role);
        mysqli_stmt_fetch($role_check_stmt);
        mysqli_stmt_close($role_check_stmt);

        if ($role === 'admin') {
            echo json_encode([
                "status" => "error",
                "message" => "Admins are not allowed to join challenges."
            ]);
            exit();
        }
    }


    // Check if the user has already joined the challenge
    $check_sql = "SELECT id FROM join_challenge WHERE user_id = ? AND challenge_id = ?";
    if ($check_stmt = mysqli_prepare($conn, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $challenge_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // User already joined the challenge
            echo json_encode([
                "status" => "warning",
                "message" => "You have already joined this challenge."
            ]);
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }

    // Insert the join record into join_challenge table
    $sql = "INSERT INTO join_challenge (user_id, challenge_id) 
            SELECT ?, ? FROM challenges WHERE challenge_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $challenge_id, $challenge_id);
        if (mysqli_stmt_execute($stmt)) {
            // Update the number of participants in challenge_program table
            $update_sql = "UPDATE challenges SET number_participants = number_participants + 1 WHERE challenge_id = ?";
            if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                mysqli_stmt_bind_param($update_stmt, "i", $challenge_id);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }

            // Fetch the updated number of participants
            $fetch_sql = "SELECT number_participants FROM challenges WHERE challenge_id = ?";
            if ($fetch_stmt = mysqli_prepare($conn, $fetch_sql)) {
                mysqli_stmt_bind_param($fetch_stmt, "i", $challenge_id);
                mysqli_stmt_execute($fetch_stmt);
                mysqli_stmt_bind_result($fetch_stmt, $number_participants);
                mysqli_stmt_fetch($fetch_stmt);
                mysqli_stmt_close($fetch_stmt);
            }

            echo json_encode([
                "status" => "success",
                "message" => "Successfully joined the challenge!",
                "number_participants" => $number_participants
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error: Could not join the challenge."
            ]);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
?>