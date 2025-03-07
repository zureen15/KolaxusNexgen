<?php
// Include config file
require_once "../database/config.php";

// Prepare the SQL query
$sql = "SELECT 
            cp.challenge_id, 
            cp.program_name, 
            u.email AS student_email,
            u.phone AS student_phone
        FROM challenges cp
        JOIN join_challenge jc ON cp.challenge_id = jc.challenge_id
        JOIN users u ON jc.user_id = u.user_id";

// Execute the query and check for errors
if ($result = mysqli_query($conn, $sql)) {
    // Query executed successfully
} else {
    // Handle query error
    error_log("Database query failed: " . mysqli_error($conn));
    die("An error occurred. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../frontend/css/side_user.css?v=1">
    <link rel="stylesheet" href="../frontend/css/table.css?v=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <title>Admin - Challenge Program</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <?php include "../frontend/sidebar/side.php"; ?>

    <section id="content">
        <?php include "../frontend/sidebar/header.php"; ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table id="challengeJoinTable" class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No</th>";
                                echo "<th>Challenge Name</th>";
                                echo "<th>Student Email</th>";
                                echo "<th>Student No. Phone</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $i = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['program_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['student_email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['student_phone']) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close the database connection
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </main>

    </section>

    <script src="../frontend/js/side.js?v=1"></script>
    <script src="../frontend/js/table.js?v=1"></script>
</body>

</html>