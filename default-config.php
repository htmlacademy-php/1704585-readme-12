<?php
if (file_exists('local_config.php')) {
    require_once('local_config.php');
} else {
    echo("Файл не существует");
}

$transport_username = $mailtrap_username ?? 'user@mailtrap.io';
$transport_password = $mailtrap_password ?? 'password';

$localhost = $local_host ?? '127.0.0.1';
$db_user = $db_local_user ?? 'root';
$db_password = $db_local_password ?? 'password';
$db_session = $db_local_session ?? 'readme';
?>