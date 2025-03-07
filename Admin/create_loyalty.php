<?php
require '../backend/admin_loyalty/create_loyalty.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Create Loyalty Program</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="none" stroke="currentColor" stroke-width=".5" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>') no-repeat right .75rem center/8px 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2>Create Voucher or Discount</h2>
                            <p>Please fill this form and submit to add loyalty program record to the database.</p>
                        </div>
                        <div class="card-body">
                            <form action="create_loyalty.php" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Upload Image:</label>
                                        <input type="file" id="image" name="image"
                                            class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                                        <span class="invalid-feedback"><?php echo $image_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Program Name</label>
                                        <input type="text" id="program_name" name="program_name"
                                            class="form-control <?php echo (!empty($program_name_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $program_name; ?>">
                                        <span class="invalid-feedback"><?php echo $program_name_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Loyalty Type </label>
                                        <select id="loyalty_type" name="loyalty_type"
                                            class="form-select form-control <?php echo (!empty($loyalty_type_err)) ? 'is-invalid' : ''; ?>">
                                            <option selected disabled value="">-- Choose --</option>
                                            <option value="Food & Beverage" <?php echo ($loyalty_type == 'Food & Beverage') ? 'selected' : ''; ?>>Food & Beverage</option>
                                            <option value="Entertainment" <?php echo ($loyalty_type == 'Entertainment') ? 'selected' : ''; ?>>Entertainment</option>
                                            <option value="Health & Beauty" <?php echo ($loyalty_type == 'Health & Beauty') ? 'selected' : ''; ?>>Health & Beauty</option>
                                            <option value="Shopping" <?php echo ($loyalty_type == 'Shopping') ? 'selected' : ''; ?>>
                                                Shopping</option>
                                            <option value="Travel & Services" <?php echo ($loyalty_type == 'Travel & Services') ? 'selected' : ''; ?>>Travel & Services</option>
                                        </select>
                                        <span class="invalid-feedback"><?php echo $loyalty_type_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Voucher Code</label>
                                        <input type="text" id="voucher_code" name="voucher_code"
                                            class="form-control <?php echo (!empty($voucher_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $voucher_code; ?>">
                                        <span class="invalid-feedback"><?php echo $voucher_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Description</label>
                                        <textarea name="challenge_description"
                                            class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                        <span class="invalid-feedback"><?php echo $description_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Max Claims</label>
                                        <input type="number" id="max_claims" name="max_claims"
                                            class="form-control <?php echo (!empty($max_claims_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $max_claims; ?>">
                                        <span class="invalid-feedback"><?php echo $max_claims_err; ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Start Date:</label>
                                        <input type="date" id="start_date" name="start_date"
                                            class="form-control datepicker <?php echo (!empty($start_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $start_date; ?>">
                                        <span class="invalid-feedback"><?php echo $start_err; ?></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Expiry Date:</label>
                                        <input type="date" id="expiry_date" name="expiry_date"
                                            class="form-control datepicker <?php echo (!empty($expiry_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $expiry_date; ?>">
                                        <span class="invalid-feedback"><?php echo $expiry_err; ?></span>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <a href="index_loyalty.php" class="btn btn-secondary ml-2">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>