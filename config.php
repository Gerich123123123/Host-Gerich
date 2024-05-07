<?php

$host = '127.0.0.1';
$dbname = 'guvgsgh3_1';
$username = 'guvgsgh3_1';
$password = 'Moloit56';

$panelname = 'GR PANEL';
$mainlink = 'https://';
$panellink = 'https://';
$imagelink = 'https://i.ibb.co/NjJ5mgN/image.png';
$promo = '#gerich';

$supportlink = 'https://vk.com/';
$youtubelink = 'https://www.youtube.com/';
$vkontaktelink = 'https://vk.com/';
$telegramlink = 'https://t.me';
//Платежка GidPay.ru
$gshop_id = '';
$gid_pubkey = '';
$gid_seckey = '';

$siteckey_captcha = '6LdbnNIpAAAAABsG0kptH34b7qgf_4dXghc884fe';
$secret_captcha = '6LdbnNIpAAAAAAgOTZZPsfwqyI4bAKNqPEFFpksQ';

$ip_lan = '';
$pass_lan = '';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $username, $password);
    $con-> query("SET character_set_results = utf8mb4");
	$con-> query("SET NAMES 'utf8mb4'");
} catch (PDOException $exception) {
    echo "Ошибка: {$exception->getMessage()}";
}
?>