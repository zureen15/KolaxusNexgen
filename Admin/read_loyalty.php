<?php
if (isset($_GET["voucher_id"]) && !empty(trim($_GET["voucher_id"]))) {
    require "../database/config.php";

    $sql = "SELECT lp.*, COUNT(uv.id) as claims FROM loyalty_program lp LEFT JOIN user_vouchers uv ON lp.voucher_id = uv.voucher_id WHERE lp.voucher_id = ? GROUP BY lp.voucher_id";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["voucher_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $program_name = $row["program_name"];
                $loyalty_type = $row["loyalty_type"];
                $voucher_code = $row["voucher_code"];
                $description = $row["challenge_description"];
                $start_date = $row["start_date"];
                $expiry_date = $row["expiry_date"];
                $max_claims = $row["max_claims"];
                $image = $row["image"];
                $claims = $row["claims"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error_loyalty.php");
                exit();
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($conn);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error_loyalty.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>View Voucher</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/read1.css?v=1">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2>View Voucher</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <p><img src="upload/<?php echo $image; ?>" alt="Current Image" width="100"
                                                height="100"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Program Name</label>
                                        <p><b><?php echo $program_name; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loyalty Type</label>
                                        <p><b><?php echo $loyalty_type; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Voucher Code</label>
                                        <p><b><?php echo $voucher_code; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <p><b><?php echo $description; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Max Claims</label>
                                        <p><b><?php echo $max_claims; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Claims</label>
                                        <p><b><?php echo $claims; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <p><b><?php echo $start_date; ?></b></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <p><b><?php echo $expiry_date; ?></b></p>
                                    </div>
                                </div>
                            </div>
                            <p><a href="index_loyalty.php" class="btn btn-primary">Back</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>