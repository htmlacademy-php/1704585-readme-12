<?php
require_once('helpers.php');
require_once('init.php');

$add_form = false;
$id = 0;
$posts = [];

if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    die();
}
    
mysqli_set_charset($db_link, "utf8");
    
$post_types = make_select_query($db_link, "SELECT * FROM types;");

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id = null;
}

$condition = "";

if ($id) {
    $condition = "AND tp.id = $id";
}
    
$user_id = $user['id'];

$posts = make_select_query(
    $db_link,
    "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class,
        COUNT(c.id) AS comments, COUNT(l.id) AS likes
    FROM posts p 
        JOIN users us ON p.user_id = us.id
        JOIN types tp ON p.post_type = tp.id 
        LEFT JOIN comments c ON p.id = c.post_id
        LEFT JOIN likes l ON l.post_id = p.id 
    WHERE p.user_id IN (SELECT to_user_id FROM subscriptions WHERE user_id = $user_id)" .
    $condition .
    " GROUP BY p.id;"
);

$post_content = include_template('posts-page.php', [
    'posts' => filter_posts($posts)
    ]);
$page_content = include_template('feed-main.php', [
    'content' => $post_content,
    'id' => $id,
    'post_types' => filter_posts($post_types)
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
