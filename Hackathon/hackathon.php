<?php
// Include config file
require_once "../database/config.php";

// Prepare the SQL query
$sql = "SELECT 
            hp.hackathon_id, 
            hp.hackathon_name, 
            hp.hackathon_type, 
            hp.location, 
            hp.hackathon_description, 
            hp.start_date, 
            hp.end_date, 
            -- hp.contact_email, 
            -- hp.company_name, 
            hp.prizes, 
            hp.images, 
            (SELECT COUNT(*) 
             FROM join_hackathon jh 
             WHERE jh.hackathon_id = hp.hackathon_id) AS number_participants
        FROM hackathon_program hp";

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
    <link rel="stylesheet" href="../frontend/css/side.css?v=1">
    <link rel="stylesheet" href="../frontend/css/table.css?v=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <title>Admin - Hackathon Program</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <?php include "../frontend/sidebar/side.php"; ?>

    <section id="content">
        <?php include "../frontend/sidebar/header.php"; ?>
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-5 mb-3 clearfix">
                            <h2 class="pull-left">Hackathon Program Details</h2>
                            <a href="create_hackathon.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i>
                                Add New Hackathon Program</a>
                        </div>
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table id="hackathonTable" class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No</th>";
                                echo "<th>Image</th>";
                                echo "<th>Hackathon Name</th>";
                                echo "<th>Hackathon Type</th>";
                                echo "<th>Location</th>";
                                echo "<th>Description</th>";
                                echo "<th>Start Date</th>";
                                echo "<th>End Date</th>";
                                //echo "<th>Contact Email</th>";
                                //echo "<th>Company Name</th>";
                                echo "<th>Prizes</th>";
                                echo "<th>Number of Participants</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $i = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    $images = explode(',', $row['images']);
                                    echo "<td class='image-container'>";
                                    foreach ($images as $img) {
                                        echo "<img src='upload/" . htmlspecialchars($img) . "' alt='Image'>";
                                    }
                                    echo "</td>";
                                    echo "<td>" . htmlspecialchars($row['hackathon_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['hackathon_type']) . "</td>";
                                    echo "<td>" . (is_null($row['location']) ? 'This event is online' : htmlspecialchars($row['location'])) . "</td>";
                                    echo "<td>" . htmlspecialchars_decode($row['hackathon_description']) . "</td>";
                                    echo "<td>" . date("F j, Y (h:i A)", strtotime($row['start_date'])) . "</td>";
                                    echo "<td>" . date("F j, Y (h:i A)", strtotime($row['end_date'])) . "</td>";
                                    //echo "<td>" . htmlspecialchars($row['contact_email']) . "</td>";
                                    //echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['prizes']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['number_participants']) . "</td>";
                                    echo "<td>";
                                    echo '<a href="read_hackathon.php?hackathon_id=' . htmlspecialchars($row['hackathon_id']) . '" class="btn btn-info btn-sm mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="update_hackathon.php?hackathon_id=' . htmlspecialchars($row['hackathon_id']) . '" class="btn btn-warning btn-sm mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<button class="btn btn-danger btn-sm" title="Delete Record" data-toggle="tooltip" onclick="Delete(' . htmlspecialchars($row['hackathon_id']) . ')"><span class="fa fa-trash"></span></button>';
                                    echo "</td>";
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
    <script>
        function Delete(hackathonId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_hackathon.php?hackathon_id=' + hackathonId;
                }
            });
        }
    </script>
</body>

</html>