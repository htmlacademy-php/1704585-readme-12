<?php
session_start();
require_once('default-config.php');

$is_auth = 0;

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
} else {
    $is_auth = 1;
}

$user = $_SESSION['user'];

$header_my_nav = [
    [
        'page' => 'popular', 
        'title' => 'Популярный контент'
    ],
    [
        'page' => 'feed', 
        'title' => 'Моя лента'
    ],
    [
        'page' => 'messages', 
        'title' => 'Личные сообщения'
    ]
];

$db_link = mysqli_connect($localhost, $db_user, $db_password, $db_session);
?>