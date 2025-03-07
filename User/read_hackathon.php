<?php
require '../backend/user/read_hackathon.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>View Hackathon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../frontend/css/side_user.css?v=1">
    <link rel="stylesheet" href="../frontend/css/dashboard.css?v=1">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include '../frontend/sidebar/side_user.php'; ?>

    <section id="content">

        <?php include '../frontend/sidebar/header_user.php'; ?>

        <main class="pull-left">
            <div class="col-md-12">
                <div class="container mt-5">
                    <h1 class="pull-left"><?php echo $hackathon_name; ?></h1>
                    <section class="details">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <?php
                                    $images = explode(',', $image);
                                    foreach ($images as $img) {
                                        echo '<img src="../Hackathon/upload/' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="Image" width="150" height="150" class="hackathon-image">';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>Type:</strong> <?php echo $hackathon_type; ?></p>
                                <p><strong>Location:</strong>
                                    <?php
                                    if ($hackathon_type === 'Online') {
                                        echo 'This event is online.';
                                    } else {
                                        echo $location;
                                    }
                                    ?>
                                </p>
                                <p><strong>Company:</strong> <?php echo $company; ?></p>
                                <p><strong>Start Date:</strong> <?php echo $start_date; ?></p>
                                <p><strong>End Date:</strong> <?php echo $end_date; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contact Person:</strong> <?php echo $contact_person; ?></p>
                                <p><strong>Email:</strong> <?php echo $contact_email; ?></p>
                                <p><strong>Prizes:</strong> <?php echo $prizes; ?></p>
                                <p><strong>Registration Deadline:</strong> <?php echo $registration_deadline; ?></p>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>Description:</strong> <?php echo $description; ?></p>
                                <p><strong>Rules:</strong> <?php echo $rules_guidelines; ?></p>
                                <p><strong>Website:</strong> <a href="<?php echo $website; ?>"
                                        target="_blank"><?php echo $website; ?></a></p>
                            </div>
                            <div class="col-md-6">
                                <button class="btn join-btn" data-hackathon-id="<?php echo $param_id; ?>">Join
                                    Hackathon</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <?php include '../frontend/sidebar/footer.html'; ?>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../frontend/js/side.js?v=1"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.join-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const hackathonId = this.dataset.hackathonId;

                    fetch('join_hackathon.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            hackathon_id: hackathonId
                        })
                    })
                        .then(response => response.json()) // Parse the JSON response
                        .then(data => {
                            if (data.status === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.message,
                                }).then(() => {
                                    location.reload(); // Reload the page to update participant count
                                });
                            } else if (data.status === "warning") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Warning',
                                    text: data.message,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again later.',
                            });
                        });
                });
            });
        });
    </script>


</body>

</html>