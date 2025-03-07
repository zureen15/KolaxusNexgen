<?php
require '../backend/user_loyalty/redeem_voucher.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../frontend/css/side.css?v=1">
    <link rel="stylesheet" href="../frontend/css/loyalty.css?v=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>User - Loyalty Program</title>
</head>

<body>
    <?php include "../frontend/sidebar/side_user.php"; ?>

    <section id="content">
        <?php include "../frontend/sidebar/header.php"; ?>
        <main class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-5 mb-3 clearfix">
                            <h2 class="pull-left">Loyalty Program Details</h2>
                        </div>
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<div class="row">';
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<div class="col-md-4">';
                                    echo '<div class="card mb-4">';
                                    echo '<img src="../Admin/upload/' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Loyalty Program Image">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($row['program_name']) . '</h5>';
                                    echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['loyalty_type']) . '</h6>';
                                    echo '<h6 class="card-subtitle mb-2 text-muted">Voucher Code: ' . htmlspecialchars($row['voucher_code']) . '</h6>';
                                    echo '<p class="card-text">' . htmlspecialchars($row['challenge_description']) . '</p>';
                                    echo '<p class="card-text">Expiry Date: ' . htmlspecialchars($row['expiry_date']) . '</p>';
                                    echo '<a href="../User/read_loyalty.php?voucher_id=' . htmlspecialchars($row['voucher_id']) . '" class="card-link">View</a>';
                                    echo '<form action="redeem_voucher.php" method="post" style="margin-top: 10px;">';
                                    echo '<input type="hidden" name="voucher_id" value="' . htmlspecialchars($row['voucher_id']) . '">';
                                    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
                                    echo '<input type="submit" class="btn btn-primary" value="Claim Voucher">';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
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

    <script src="../frontend/js/side.js?v=1"></script>
</body>

</html>