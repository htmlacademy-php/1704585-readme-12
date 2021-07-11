<?php
require_once('helpers.php');
require_once('default-config.php');

session_start();

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    exit();
}

$errors = [];
$post = [];
$user = [];

$db_link = mysqli_connect($localhost, $db_user, $db_password, $db_session);
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    die();
}
    
mysqli_set_charset($db_link, "utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = $_POST;

    $required_fields = ['login', 'password'];
    foreach ($required_fields as $field) {
        if (empty($post[$field])) {
            $errors[$field] = 'Поле должно быть заполнено';
        }
    }

    if (empty($errors)) {
        $login = mysqli_real_escape_string($db_link, $post['login']);
        $sql = "SELECT * FROM users WHERE name = '$login'";
        $user = make_select_query($db_link, $sql, true);
            
        if (empty($errors) && $user) {
            if (password_verify($post['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Пароли не совпадают';
            }
        } else {
            $errors['login'] = 'Неверный логин';
        }
    }

    if (empty($errors)) {
        header("Location: /feed.php");
        exit();
    }
}

$layout_content = include_template('layout-main.php', [
    'title' => 'readme: блог, каким он должен быть',
    'errors' => $errors
]);

print($layout_content);
