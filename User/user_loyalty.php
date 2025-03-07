<?php
require '../backend/user_loyalty/loyalty.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="../frontend/css/side_user.css?v=1" rel="stylesheet">
    <link href="../frontend/css/loyalty.css?v=1" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>User - Loyalty Program</title>
</head>

<body>
    <?php include "../frontend/sidebar/side_user.php"; ?>

    <section id="content">
        <?php include "../frontend/sidebar/header_user.php"; ?>
        <main class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-5 mb-3 clearfix">
                            <h2 class="pull-left">Loyalty Program Details</h2>
                            <form method="GET" action="user_loyalty.php">
                                <div class="form-row align-items-end">
                                    <div class="form-group col-md-8">
                                        <label for="loyalty_type"></label>
                                        <select id="loyalty_type" name="loyalty_type" class="form-select form-control">
                                            <option selected disabled value="">-- Choose --</option>
                                            <option value="Food & Beverage">Food & Beverage</option>
                                            <option value="Entertainment">Entertainment</option>
                                            <option value="Health & Beauty">Health & Beauty</option>
                                            <option value="Shopping">Shopping</option>
                                            <option value="Travel & Services">Travel & Services</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php
                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<div class="row">';
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<div class="col-md-4 col-sm-6">';
                                    echo '<div class="card mb-4">';
                                    echo '<img src="../Admin/upload/' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Loyalty Program Image">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($row['program_name']) . '</h5>';
                                    echo '<h6 class="card-subtitle mb-2">' . htmlspecialchars($row['loyalty_type']) . '</h6>';
                                    echo '<h6 class="card-subtitle mb-2">Voucher Code: ' . htmlspecialchars($row['voucher_code']) . '</h6>';
                                    echo '<p class="card-text">' . htmlspecialchars($row['challenge_description']) . '</p>';
                                    echo '<p class="card-text">Expiry Date: ' . htmlspecialchars($row['expiry_date']) . '</p>';
                                    //echo '<p class="card-text">Claims: ' . htmlspecialchars($row['claims']) . '/' . htmlspecialchars($row['max_claims']) . '</p>';
                                    echo '<a href="../User/read_loyalty.php?voucher_id=' . htmlspecialchars($row['voucher_id']) . '" class="card-link">View</a>';
                                    echo '<form action="../User/redeem_voucher.php" method="post" style="margin-top: 10px;">';
                                    echo '<input type="hidden" name="voucher_id" value="' . htmlspecialchars($row['voucher_id']) . '">';
                                    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
                                    // echo '<input type="submit" class="btn btn-primary" value="Claim Voucher">';
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

                        // mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../frontend/sidebar/footer.html'; ?>
    </section>

    <!-- <script src="../frontend/js/side.js?v=1"></script> -->
</body>

</html>