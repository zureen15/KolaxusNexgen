<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../frontend/css/side.css?v=1">

	<title>Admin Dashboard</title>
</head>

<body>

	<?php 
	require '../backend/admin_dashboard.php';

	include '../frontend/sidebar/side.php';

	?>

	<section id="content">
		<?php include '../frontend/sidebar/header.php'; ?>

		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-user'></i>
					<span class="text">
						<h3><?php echo $total_users; ?></h3>
						<p>Students</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-institution'></i>
					<span class="text">
						<h3><?php echo $total_universities; ?></h3>
						<p>Total Universities</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-book-content'></i>
					<span class="text">
						<h3><?php echo $total_courses; ?></h3>
						<p>Total Courses</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-coupon'></i>
					<span class="text">
						<h3><?php echo $total_vouchers; ?></h3>
						<p>Total Vouchers</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-flag'></i>
					<span class="text">
						<h3><?php echo $total_malaysian; ?></h3>
						<p>Total of Local Students</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-flag-alt'></i>
					<span class="text">
						<h3><?php echo $total_non_malaysian; ?></h3>
						<p>Total of International Students</p>
					</span>
				</li>
			</ul>

		</main>
	</section>

	<script src="../frontend/js/side.js?v=1"></script>
</body>

</html>