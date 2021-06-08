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
        $check_post_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM posts WHERE id = $id"));

        $user_id = $user['id'];
        $check_user_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM likes WHERE user_id = $user_id AND post_id = $id;"));
        
        if($check_post_id && !$check_user_id) {
            $like = ['0' => $user['id'], '1' => $id];
            $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?);";

            $stmt = db_get_prepare_stmt($db_link, $sql, $like);

            $result = mysqli_stmt_execute($stmt);
            if(!$result) {
                print("Ошибка запроса: " . mysqli_error($db_link));
            }
        }
    }

    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}
?>