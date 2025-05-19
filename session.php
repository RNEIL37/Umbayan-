<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login_form.php');
    exit();
}

require_once 'config.php'; // Use require_once to ensure config is loaded and not duplicated

// Escape the session ID
$user_id = mysqli_real_escape_string($conn, $_SESSION['id']);

// Fetch user information
$query = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'");
$sfs_user_row = mysqli_fetch_assoc($query);
$fac_session_id = $sfs_user_row['id'];

// If user is not found, redirect to login
if (!$sfs_user_row) {
    header('Location: login_form.php');
    exit();
}

// Assign variables
$fac_user_username1 = $sfs_user_row['u_fname'];
$fac_user_username2 = $sfs_user_row['u_lname'];
$fac_user_username = $sfs_user_row['u_fullname'];
$fac_user_username5 = $sfs_user_row['u_fname'] . ' ' . $sfs_user_row['u_lname'];
$fac_user_address = $sfs_user_row['u_complete_add'];
$fac_session_id = $sfs_user_row['id'];
$fac_contacts11 = $sfs_user_row['contactno'];
?>