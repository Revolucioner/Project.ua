<?php
session_start();
require('connectdb.php');

$name = filter_var(trim($_POST['name']),FILTER_SANITIZE_STRING);
$mail = filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
$pas = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
$pas2 = filter_var(trim($_POST['password_confirmation']),FILTER_SANITIZE_STRING);

if ($name == ''){
    $_SESSION['register'] = 'error_name';
    header('location: /register.php');
    die();
}elseif ($mail == ''){
    $_SESSION['register'] = 'error_mail';
    header('location: /register.php');
    die();
}elseif ($pas == ''){
    $_SESSION['register'] = 'error_pas';
    header('location: /register.php');
    die();
}elseif ($pas != $pas2){
    $_SESSION['register'] = 'error_pas2';
    header('location: /register.php');
    die();
}
$pas = md5($pas);

$query1 = "SELECT * FROM users WHERE name = '{$name}' && mail = '{$mail}'";
$query1 = mysqli_query($connect, $query1);
$result1 = mysqli_fetch_assoc($query1);
if (!$result1){
    $query2 = "INSERT INTO users (name, mail ,pas) VALUES ('{$name}', '{$mail}', '{$pas}')";
    $create2 = mysqli_query($connect, $query2);
    if (!$create2){
        echo 'BORODA';
    }else{
        $query = "SELECT * FROM users WHERE name = '{$name}' && pas = '{$pas}' && mail = '{$mail}'";
        $results = mysqli_query($connect, $query);
        $result = mysqli_fetch_assoc($results);
        $_SESSION['user_name'] = $result['name'];
        $_SESSION['user_id'] = $result['id'];
        mysqli_free_result($results);
    }
}else{
    $_SESSION['user_error'] = TRUE;
    header('location: /register.php');
    die();
}

header('location: /');
mysqli_close($connect);