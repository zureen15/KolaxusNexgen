<?php
require '../database/config.php';

//Fetch user data including university and course
$query = "
    SELECT 
        u.username, 
        u.email, u.nationality, u.phone, 
        IFNULL(un.uni_name, u.custom_universities) AS university, 
        IFNULL(c.course_name, u.custom_courses) AS course 
    FROM 
        users u 
    LEFT JOIN 
        universities un ON u.uni_id = un.uni_id 
    LEFT JOIN 
        course c ON u.course_id = c.course_id
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
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
    <!-- <link rel="stylesheet" href="../frontend/css/table.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <title>Admin Student Management</title>
</head>

<body>

    <?php include '../frontend/sidebar/side.php'; ?>

    <section id="content">
        <?php include '../frontend/sidebar/header.php'; ?>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Student Management</h1>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>List of User</h3>
                    </div>
                    <table id="userTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nationality</th>
                                <th>Number Phone</th>
                                <th>University</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo ucwords($row['email']) ?></td>
                                    <td><?php echo ucwords($row['nationality']) ?></td>
                                    <td><?php echo ucwords($row['phone']) ?></td>
                                    <td><?php echo ucwords($row['university']) ?></td>
                                    <td><?php echo ucwords($row['course']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </section>

    <script src="../frontend/js/table.js?v=1"></script>
    <script src="../frontend/js/side.js?v=1"></script>

</body>

</html>