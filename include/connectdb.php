<?php
$connect = mysqli_connect('localhost', 'root','', 'project');
if (mysqli_connect_error()){
    die(mysqli_connect_error() . mysqli_connect_errno());
}