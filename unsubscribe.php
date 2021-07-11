<?php
require_once('helpers.php');
require_once('init.php');

$id = null;

if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    die();
}
    
mysqli_set_charset($db_link, "utf8");

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
<<<<<<< HEAD
=======
} else {
    $id = null;
>>>>>>> 6fd97d814d5c9346a73b9e9c74019cc6738ae586
}

if ($id) {
    $user_id = $user['id'];
    $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM subscriptions 
        WHERE user_id = $user_id AND to_user_id = $id"));
        
    if ($check_id) {
        $result = mysqli_query($db_link, "DELETE FROM subscriptions WHERE user_id = $user_id AND to_user_id = $id;");
        if (!$result) {
            print("Ошибка запроса: " . mysqli_error($db_link));
            die();
        }
    }
}

header("Location: profile.php?id=" . $id);
