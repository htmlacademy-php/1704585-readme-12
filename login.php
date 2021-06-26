<?php
require_once('helpers.php');
require_once('default-config.php');

session_start();

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    exit();
}

$is_auth = 0;
$errors = [];

$db_link = mysqli_connect($localhost, $db_user, $db_password, $db_session);
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post = $_POST;

        $required_fields = ['email', 'password'];
        foreach ($required_fields as $field) {
            if (empty($post[$field])) {
                $errors[$field] = "Поле должно быть заполнено";
            }
        }

        $user = [];

        if (empty($errors)) {
            $email = mysqli_real_escape_string($db_link, $post['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Неверный формат почты";
            } else {
                $user = make_select_query($db_link, "SELECT * FROM users WHERE email = '$email'", true);
            }

            if ($user) {
                if (password_verify($post['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Пароли не совпадают';
                }
            } else {
                $errors['email'] = "Неверная почта";
            }
        }

        if (empty($errors)) {
            header("Location: /feed.php");
            exit();
        }
    }
}

$page_content = include_template('login-main.php', [
    'errors' =>$errors
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'readme: авторизация',
    'is_login' => true
    ]);

print($layout_content);
