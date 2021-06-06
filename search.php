<?php
require_once('helpers.php');
require_once('init.php');

$add_form = false;

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    $search = $_GET['query'] ?? '';

    if ($search) {
        $search = trim($search);
        $is_tag = substr($search, 0, 1);
        
        if ($is_tag === '#') {
            $tag_name = substr($search, 1, strlen($search) - 1);
            $tag_name = mysqli_real_escape_string($db_link, $tag_name);

            $posts = make_select_query ($db_link, 
                "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(c.id) AS comments, COUNT(l.id) AS likes
                FROM posts p 
                    JOIN users us ON p.user_id = us.id
                    JOIN types tp ON p.post_type = tp.id 
                    LEFT JOIN comments c ON p.id = c.post_id
                    LEFT JOIN likes l ON l.post_id = p.id
                    WHERE p.id IN (SELECT post_id FROM posts_hashtags ph JOIN hashtags h ON ph.hash_id = h.id WHERE hash_name = '$tag_name')
                    GROUP BY p.id ORDER BY published_at");
        } else {
            $sql = "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(c.id) AS comments, COUNT(l.id) AS likes
                FROM posts p 
                    JOIN users us ON p.user_id = us.id
                    JOIN types tp ON p.post_type = tp.id 
                    LEFT JOIN comments c ON p.id = c.post_id
                    LEFT JOIN likes l ON l.post_id = p.id
                    WHERE MATCH(p.title, p.content) AGAINST(?)
                    GROUP BY p.id;";
            $stmt = db_get_prepare_stmt($db_link, $sql, [$search]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } 
        }

        if ($posts) {
            $page = 'main';
        } else {
            $page = 'none';
        }
    } else {
        $page = 'none';
    }
}

$page_content = include_template('search-' . $page . '.php', [
    'posts' => $posts,
    'search' => $search
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'search' => $search,
    'title' => 'readme: страница результатов поиска',
    'user' => $user,
    'header_my_nav' => $header_my_nav,
    'is_auth' => $is_auth,
    'add_form' => $add_form
]);

print($layout_content);
?>