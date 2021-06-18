<?php
require_once('helpers.php');
if (file_exists('local_config.php')) {
    require_once('local_config.php');
} else {
    echo("Файл не существует");
}

$is_auth = 0;
$errors = [];

$db_link = mysqli_connect($localhost, $db_user, $db_password, $db_session);
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required_fields = ['email', 'login', 'password', 'password-repeat'];
        $post = $_POST;

        foreach ($required_fields as $field) {
            $result = validateFilled($post[$field], $field);
            if ($result) {
                $errors[$field] = $result;
            }
        }

        if (isset($post['email']) && !empty($post['email'])) {
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'email. Неверный формат электронной почты.';
            }
        }

        if (empty($errors)) {
            $user_fields = ['login', 'password', 'email'];
            $img_key = '';
            $img_value = '';

            $email = mysqli_real_escape_string($db_link, $post['email']);
            $email_check = make_select_query($db_link, "SELECT id FROM users WHERE email = '$email'", true);
            $login = mysqli_real_escape_string($db_link, $post['login']);
            $login_check = make_select_query($db_link, "SELECT id FROM users WHERE name = '$login'", true);
            if ($email_check) {
                $errors['email'] = 'Пользователь с таким email уже зарегистрирован.';
            } elseif ($login_check) {
                $errors['login'] = 'Пользователь с таким логином уже зарегистрирован.';
            } elseif ($post['password'] !== $post['password-repeat']) {
                $errors['password'] = 'Пароли не совпадают.';
                $errors['password-repeat'] = 'Пароли не совпадают.';
            } else {
                if (!empty($_FILES['userpic-file']['name'])) {
                    $tmp_name = $_FILES['userpic-file']['tmp_name'];
                    $img_name = $_FILES['userpic-file']['name'];
    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $file_type = finfo_file($finfo, $tmp_name);
                    $valid_type = validateFileType($file_type, ['image/png', 'image/jpeg', 'image/gif']);
                    if(!$valid_type) {
                        move_uploaded_file($tmp_name, 'img/' . $img_name);
                        $post['avatar_img'] = $img_name;
                        $img_key = ', avatar_img';
                        $img_value = ', ?';
                        $user_fields[3] = 'avatar_img';
                    }
                    else {
                        $errors['file'] = $valid_type;
                    }
                }
                if (empty($errors)) {
                    $password = password_hash($post['password'], PASSWORD_DEFAULT);
                    $post['password'] = $password;

                    $post = fillArray($post, $user_fields);
                    $sql = 'INSERT INTO users (name, password, email' . $img_key . ') VALUES (?, ?, ?' . $img_value . ');';
                    $stmt = db_get_prepare_stmt($db_link, $sql, $post);
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        $user_id = mysqli_insert_id($db_link);

                        header("Location: /index.php");
                    } else {
                        print("Ошибка запроса: " . mysqli_error($db_link));
                    }
                }
            }
        }
    }
}

$page_content = include_template('reg-main.php', [
    'errors' =>$errors
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'readme: регистрация'
    ]);

print($layout_content);
?>