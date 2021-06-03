<?php
require_once('helpers.php');
require_once('init.php');

$is_auth = 1;
$add_form = false;

$id = 0;
$post = [];

$id = filter_input(INPUT_GET, 'id');

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM posts WHERE id = $id"));

    if($check_id === 0) {
        http_response_code(404);
        exit();
    } else {
        $post = make_select_query($db_link, 
            "SELECT p.*, type_name AS type, icon_class AS class, COUNT(c.id) AS comments, COUNT(l.id) AS likes 
            FROM posts p
            JOIN types tp ON p.post_type = tp.id
            LEFT JOIN comments c ON p.id = c.post_id
            LEFT JOIN likes l ON p.id = l.post_id
            GROUP BY p.id
            HAVING p.id = $id;",
            true
        );
        
        $user_id = $post['user_id'];
        $post_user = make_select_query($db_link, 
            "SELECT u.*, COUNT(sub.id) AS subs, COUNT(p.id) AS posts
            FROM users u 
            LEFT JOIN subscriptions sub ON u.id = sub.user_id
            LEFT JOIN posts p ON u.id = p.user_id
            GROUP BY u.id
            HAVING u.id = $user_id;",
            true
        );

        $comments = make_select_query($db_link, 
        "SELECT comment, published_at, name, avatar_img 
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = $id
        ORDER BY published_at DESC LIMIT 4;"
        );

        $tags = make_select_query($db_link, 
            "SELECT hash_name AS tag FROM hashtags h 
            JOIN posts_hashtags ph ON h.id = ph.hash_id
            WHERE ph.post_id = $id;"
        );
    }
}

$post_file = "post-" . $post['class'] . ".php";

$post_content = include_template($post_file, [
    'post' => $post
    ]);
$page_content = include_template('post-main.php', [
    'id' => $id,
    'content' => $post_content,
    'post' => $post,
    'user' => $post_user,
    'comments' => $comments,
    'tags' => $tags
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user' => $user,
    'header_my_nav' => $header_my_nav,
    'title' => 'readme: публикация',
    'add_form' => $add_form
    ]);

print($layout_content);
?>