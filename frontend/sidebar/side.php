<?php
$current_page = htmlspecialchars(basename($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../frontend/css/side.css">
</head>

<body>

    <section id="sidebar" class="active">
        <a href="#" class="brand">
        <i class='img'><img src="../frontend/sidebar/img/kolaxus.jpg"></i>
            <!-- <span class="text">Admin</span> -->
        </a>
        <ul class="side-menu top">
            <li class="<?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>">
                <a href="../Admin/admin_dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'manage.php') ? 'active' : '' ?>">
                <a href="../Admin/manage.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Student Management</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'hackathon.php') ? 'active' : '' ?>">
                <a href="../Hackathon/hackathon.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Hackathon</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'user_hackathon.php') ? 'active' : '' ?>">
                <a href="../Hackathon/user_hackathon.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Student Hackathon List</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'index_loyalty.php') ? 'active' : '' ?>">
                <a href="../Admin/index_loyalty.php">
                    <i class='bx bxs-discount'></i>
                    <span class="text">Loyalty Program</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'challenge.php') ? 'active' : '' ?>">
                <a href="../Challenge/challenge.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Challenge</span>
                </a>
            </li>
            <li class="<?= ($current_page == 'user_challenge.php') ? 'active' : '' ?>">
                <a href="../Challenge/user_challenge.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Student Challenge List</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="../frontend/logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    
</body>

</html>