<?php
require_once('helpers.php');
require_once('init.php');

$post = [];

if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    die();
}
    
mysqli_set_charset($db_link, "utf8");

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id = null;
}

if ($id) {
    $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM posts WHERE id = $id"));
        
    if ($check_id) {
        $post = make_select_query(
            $db_link,
            "SELECT title, content, author, img, video, link, user_id, post_type, is_repost, author_id, repost_count
        FROM posts WHERE id = $id;",
            true
        );

        if ($post['user_id'] === $user['id'] or $post['author_id'] === $user['id']) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
                
        $post['is_repost'] = '1';
        $post['repost_count'] = $post['repost_count'] + 1;
        $post['user_id'] = $user['id'];

        if (!$post['author_id']) {
            $post['author_id'] = $post['user_id'];
        }

        $sql = "INSERT INTO posts (title, content, author, img, video, link, user_id,
            post_type, is_repost, author_id, repost_count)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = db_get_prepare_stmt($db_link, $sql, $post);

        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("Location: /profile.php?id=" . $user['id']);
            exit();
        }
        
        print("Ошибка запроса: " . mysqli_error($db_link));
    }
}
