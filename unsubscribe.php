<?php
require_once('helpers.php');
require_once('init.php');

if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = null;
    }

    if ($id) {
        $user_id = $user['id'];
        $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM subscriptions WHERE user_id = $user_id AND to_user_id = $id"));
        
        if ($check_id) {
            $result = mysqli_query($db_link, "DELETE FROM subscriptions WHERE user_id = $user_id AND to_user_id = $id;");
            if (!$result) {
                print("Ошибка запроса: " . mysqli_error($db_link));
            }
        }
    }

    header("Location: profile.php?id=" . $id);
}
