<?php
/* Copyright (c) 2024 FG PANEL| DEVELOPER - vk.com/maksweeb */
session_start();
require('engine/config.php');

$email = $_SESSION['email'];
$pass = $_SESSION['pass'];

$query = $con->prepare("SELECT * FROM users WHERE Email = ? AND Password = ?");
$query->execute([$email, $pass]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user || $_SESSION['auth'] == false) {
    header('Location: /authorization');
    exit();       
}

// Проверка, является ли пользователь администратором
if ($user['Admin'] != 1) {
    // Если не администратор, перенаправляем на страницу с сообщением об ошибке или другую страницу
    header('Location: /error_page');
    exit();
}

$action = trim($_GET['action'] ?? '');

switch ($action) {
    case 'index':
        include('includes/admin/index.php');
        break;
	//users
    case 'users':
        include('includes/admin/users/index.php');
        break;
	case 'users-edit':
        include('includes/admin/users/edit.php');
        break;
    case 'invoices':
        include('includes/admin/users/invoices.php');
        break;
	//Промо
	case 'promo':
		include('includes/admin/promo/index.php');
		break;
	case 'promoadd':
		include('includes/admin/promo/add.php');
		break;
	case 'promoedit':
		include('includes/admin/promo/edit.php');
		break;
	//Новости
	case 'news':
		include('includes/admin/news/index.php');
		break;
	case 'newsadd':
		include('includes/admin/news/add.php');
		break;
	case 'newsedit':
		include('includes/admin/news/edit.php');
		break;
	//shop
    case 'shops':
        include('includes/admin/shop/index.php');
        break;
    case 'shopadd':
        include('includes/admin/shop/add.php');
        break;
	case 'shops-edit':
        include('includes/admin/shop/edit.php');
        break;
	//Покупки
    case 'purchases':
        include('includes/admin/purchases/index.php');
        break;
	case 'purchases-edit':
        include('includes/admin/purchases/edit.php');
        break;
	//Система
	case 'system':
        include('includes/admin/system.php');
        break;
	//Дефолт
    default:
        include('includes/admin/index.php');
        break;
}
?>
