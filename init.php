<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
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
?>