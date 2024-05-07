<?php
/*Copyright (c) 2024 FG PANEL| DEVELOPER - vk.com/maksweeb */
session_start();
require('engine/config.php');

$email = $_SESSION['email'];
$pass = $_SESSION['pass'];

$query = $con->prepare("SELECT * FROM users WHERE Email = ? AND Password = ?");
$query->execute([$email, $pass]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if(!$user || $_SESSION['auth'] == false)
{
    header('Location: /authorization');
    exit();       
}

if(trim($_GET['action']) == 'info')
{
    include('includes/temp/shop-info.php');
}
else if(trim($_GET['action']) == 'terms_of_use')
{
    include('includes/temp/terms_of_use.php');
}
else include('includes/temp/shop-main.php');
?>