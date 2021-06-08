<?php
require_once('helpers.php');
require_once('init.php');

$add_form = false;

$page_types = [
    [
        'page' => 'posts', 
        'title' => 'Посты'
    ],
    [
        'page' => 'likes', 
        'title' => 'Лайки'
    ],
    [
        'page' => 'subscriptions', 
        'title' => 'Подписки'
    ]
];

if (isset($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_DEFAULT);
} else {
    $page = 'posts';
}

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
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
        $user_profile = make_select_query($db_link, 
        "SELECT u.id, u.name, u.avatar_img, u.created_at, COUNT(p.id) AS posts_count 
            FROM users u JOIN posts p ON u.id = p.user_id
            WHERE u.id = $id
        GROUP BY u.id", true);

        $subs = make_select_query($db_link,
        "SELECT COUNT(s.id) AS subs 
            FROM users u JOIN subscriptions s ON u.id = s.to_user_id
            WHERE u.id = $id
        GROUP BY u.id", true);

        $user_id = $user['id'];
        $is_subscribe = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM subscriptions WHERE user_id = $user_id AND to_user_id = $id"));
    }

    switch ($page) {
        case 'likes':
            $content = make_select_query($db_link, 
            "SELECT us.id AS user_id, us.name, us.avatar_img, pt.id AS post_id, pt.img, tp.icon_class AS type
                FROM users us, posts pt JOIN types tp ON pt.post_type = tp.id
                WHERE (us.id, pt.id) IN (SELECT u.id, p.id
                    FROM likes l JOIN users u ON l.user_id = u.id 
                    JOIN posts p ON p.id = l.post_id) 
                AND pt.id IN (SELECT id FROM posts WHERE user_id = $id);");

            break;
        case 'subscriptions':
            $content = make_select_query($db_link, 
            "SELECT u.id, u.name, u.avatar_img, u.created_at, COUNT(p.id) AS posts_count 
                FROM users u JOIN posts p ON u.id = p.user_id
                WHERE u.id IN (SELECT user_id FROM subscriptions WHERE to_user_id = $id)
            GROUP BY u.id;");

            $content_subs = make_select_query($db_link,
            "SELECT u.id, COUNT(s.id) AS subs 
                FROM users u JOIN subscriptions s ON u.id = s.to_user_id
                WHERE u.id IN (SELECT user_id FROM subscriptions WHERE to_user_id = $id)
            GROUP BY u.id");

            $current_subscriptions = make_plain_array(make_select_query($db_link, 
                "SELECT to_user_id FROM subscriptions WHERE user_id = $user_id;"));
            
            break;
        default:
            $content = make_select_query($db_link, 
            "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(c.id) AS comments, COUNT(l.id) AS likes
            FROM posts p 
                JOIN users us ON p.user_id = us.id
                JOIN types tp ON p.post_type = tp.id 
                LEFT JOIN comments c ON p.id = c.post_id
                LEFT JOIN likes l ON l.post_id = p.id 
                WHERE us.id = $id
            GROUP BY p.id");
    }
}

$inner_page = include_template('profile-' . $page . '.php', [
    'content' => $content, 
    'is_subscribe' => $current_subscriptions
]);

$page_content = include_template('profile-main.php', [
    'content' => $inner_page,
    'user_profile' => $user_profile,
    'subs' => $subs,
    'is_subscribe' => $is_subscribe,
    'page' => $page,
    'page_types' => $page_types,
    'user' => $user
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: профиль',
    'user' => $user,
    'header_my_nav' => $header_my_nav,
    'is_auth' => $is_auth,
    'add_form' => $add_form
]);

print($layout_content);
?>