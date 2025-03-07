<?php
// Process delete operation after confirmation
if (isset($_GET["hackathon_id"]) && !empty($_GET["hackathon_id"])) {
    // Include config file
    require "../database/config.php";

    // Prepare a delete statement
    $sql = "DELETE FROM hackathon_program WHERE hackathon_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["hackathon_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: hackathon.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($conn);
} else {
    // Check existence of id parameter
    header("location: error_hackathon.php");
    exit();
}
?>