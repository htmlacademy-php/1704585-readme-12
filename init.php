<?php
session_start();
if (file_exists('local_config.php')) {
    require_once('local_config.php');
} else {
    echo("Файл не существует");
}

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