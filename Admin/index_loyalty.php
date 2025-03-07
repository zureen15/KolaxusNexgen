<?php
require "../database/config.php";

// Fetch all loyalty programs with the number of claims
$sql = "SELECT lp.*, COUNT(uv.id) as claims FROM loyalty_program lp LEFT JOIN user_vouchers uv ON lp.voucher_id = uv.voucher_id GROUP BY lp.voucher_id";
$result = mysqli_query($conn, $sql);
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
    <title>Admin - Loyalty Program</title>
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
                            <h2 class="pull-left">Loyalty Program Details</h2>
                            <a href="create_loyalty.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i>
                                Add New Voucher/Discount</a>
                        </div>
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<div class="table-responsive">';
                                echo '<table id="loyaltyTable" class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No</th>";
                                echo "<th>Image</th>";
                                echo "<th>Program Name</th>";
                                echo "<th>Loyalty Type</th>";
                                echo "<th>Voucher Code</th>";
                                echo "<th>Description</th>";
                                echo "<th>Start Date</th>";
                                echo "<th>Expiry Date</th>";
                                echo "<th>Claims</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $i = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td><img src='upload/" . htmlspecialchars($row['image']) . "' alt='Image' width='50' height='50'></td>";
                                    echo "<td>" . htmlspecialchars($row['program_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['loyalty_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['voucher_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['challenge_description']) . "</td>";
                                    echo "<td>" . date("F j, Y (h:i A)", strtotime($row['start_date'])). "</td>";
                                    echo "<td>" . date("F j, Y (h:i A)", strtotime($row['expiry_date'])) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['claims']) . "/" . htmlspecialchars($row['max_claims']) . "</td>";
                                    echo "<td>";
                                    echo '<a href="read_loyalty.php?voucher_id=' . htmlspecialchars($row['voucher_id']) . '" class="btn btn-info btn-sm mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="update_loyalty.php?voucher_id=' . htmlspecialchars($row['voucher_id']) . '" class="btn btn-warning btn-sm mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<button class="btn btn-danger btn-sm" title="Delete Record" data-toggle="tooltip" onclick="confirmDelete(' . htmlspecialchars($row['voucher_id']) . ')"><span class="fa fa-trash"></span></button>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo '</div>';
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </main>

    </section>
    <script src="../frontend/js/table.js?v=1"></script>
    <script src="../frontend/js/side.js?v=1"></script>
    <script>
        function confirmDelete(voucherId) {
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
                    // Redirect to the delete page
                    window.location.href = 'delete_loyalty.php?voucher_id=' + voucherId;
                }
            });
        }
    </script>
</body>

</html>