<?php
session_start();
require('connectdb.php');
$user_id = $_SESSION ['user_id'];
$current = $_POST['current'];
$pas = $_POST['password'];
$pas2 = $_POST['password_confirmation'];

$current = md5(filter_var(trim($current),FILTER_SANITIZE_STRING));
$pas = md5(filter_var(trim($pas),FILTER_SANITIZE_STRING));
$pas2 = md5(filter_var(trim($pas2),FILTER_SANITIZE_STRING));

$query = "SELECT pas FROM users WHERE id = '{$user_id}'";
$query = mysqli_query($connect, $query);
$result = mysqli_fetch_assoc($query);
if ($result['pas'] != $current){
    $_SESSION['pas'] = 'error';
}

if ($pas !== $pas2 || $pas === 'd41d8cd98f00b204e9800998ecf8427e'){
    $_SESSION['pas_duble'] = 'error_duble';
}
if (($_SESSION['pas'] !== 'error') && ($_SESSION['pas_duble'] !== 'error_duble')){
    $query = "UPDATE users SET pas = '{$pas}' WHERE id = '{$user_id}' && pas = '{$current}'";
    $query = mysqli_query($connect, $query);
    $_SESSION['pas'] = 'ok';
}
mysqli_close($connect);
header('location: /profile.php');