<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
	header("Location: ../frontend/login_form.php");
	exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require '../database/config.php';

// Fetch total number of users
$user_query = "SELECT COUNT(*) as total_users FROM users";
$user_result = mysqli_query($conn, $user_query);
$total_users = mysqli_fetch_assoc($user_result)['total_users'];

// Fetch total number of courses
$course_query = "SELECT COUNT(*) as total_courses FROM course";
$course_result = mysqli_query($conn, $course_query);
$total_courses = mysqli_fetch_assoc($course_result)['total_courses'];

// Fetch total number of universities
$university_query = "SELECT COUNT(*) as total_universities FROM universities";
$university_result = mysqli_query($conn, $university_query);
$total_universities = mysqli_fetch_assoc($university_result)['total_universities'];

// Fetch total number of vouchers
$voucher_query = "SELECT COUNT(*) as total_vouchers FROM loyalty_program";
$voucher_result = mysqli_query($conn, $voucher_query);
$total_vouchers = mysqli_fetch_assoc($voucher_result)['total_vouchers'];

// Fetch total number of Malaysian students
$malaysian_query = "SELECT COUNT(*) as total_malaysian FROM users WHERE nationality = 'Malaysian'";
$malaysian_result = mysqli_query($conn, $malaysian_query);
$total_malaysian = mysqli_fetch_assoc($malaysian_result)['total_malaysian'];

// Fetch total number of non-Malaysian students
$non_malaysian_query = "SELECT COUNT(*) as total_non_malaysian FROM users WHERE nationality != 'Malaysian'";
$non_malaysian_result = mysqli_query($conn, $non_malaysian_query);
$total_non_malaysian = mysqli_fetch_assoc($non_malaysian_result)['total_non_malaysian'];
?>