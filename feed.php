<?php
require_once('helpers.php');
require_once('init.php');

$is_auth = 1;
$add_form = false;

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");
    
    $post_types = make_select_query($db_link, "SELECT * FROM types;");

    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = null;
    }

    $condition = "";

    if ($id) {
        $condition = "WHERE tp.id = $id";
    }
    $posts = make_select_query ($db_link, 
        "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(c.id) AS comments, COUNT(l.id) AS likes
        FROM posts p 
            JOIN users us ON p.user_id = us.id
            JOIN types tp ON p.post_type = tp.id 
            LEFT JOIN comments c ON p.id = c.post_id
            LEFT JOIN likes l ON l.post_id = p.id " .
        $condition .
        " GROUP BY p.id");
}

$page_content = include_template('feed-main.php', [
    'id' => $id,
    'post_types' => $post_types,
    'posts' => $posts
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: моя лента',
    'user' => $user,
    'page' => 'feed',
    'header_my_nav' => $header_my_nav,
    'is_auth' => $is_auth,
    'add_form' => $add_form
]);

print($layout_content);
?>