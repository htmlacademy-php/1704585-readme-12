<?php
require_once('helpers.php');
require_once('init.php');

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    if(isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = null;
    }

    if($id) {
        $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM users WHERE id = $id"));
        
        if($check_id) {
            $subscription = ['0' => $user['id'], '1' => $id];
            $sql = "INSERT INTO subscriptions (user_id, to_user_id) VALUES (?, ?);";

            $stmt = db_get_prepare_stmt($db_link, $sql, $subscription);

            $result = mysqli_stmt_execute($stmt);
            if(!$result) {
                print("Ошибка запроса: " . mysqli_error($db_link));
            }
        }
    }

    header("Location: profile.php?id=" . $id);
}
?>