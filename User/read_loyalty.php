<?php

require '../backend/user_loyalty/read_loyalty.php';

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
                                        <p><img src="../Admin/upload/<?php echo $image; ?>" alt="Voucher Image"
                                                width="200"></p>
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
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <?php if (!$already_claimed && $claims < $max_claims): ?>
                                            <form action="../backend/user_loyalty/redeem_voucher.php" method="post">
                                                <input type="hidden" name="voucher_id" value="<?php echo $param_id; ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="submit" class="btn btn-primary" value="Claim Voucher">
                                            </form>
                                        <?php elseif ($already_claimed): ?>
                                            <p class="text-danger">You have already claimed this voucher.</p>
                                        <?php else: ?>
                                            <p class="text-danger">This voucher has already been claimed by the maximum
                                                number of users.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="user_loyalty.php" class="btn btn-secondary">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>