<?php
require_once('helpers.php');
require_once('init.php');
require_once('mail.php');

$add_form = false;

$tabs = [
    [
        'tab' => 'posts', 
        'title' => 'Посты'
    ],
    [
        'tab' => 'likes', 
        'title' => 'Лайки'
    ],
    [
        'tab' => 'subscriptions', 
        'title' => 'Подписки'
    ]
];

if (isset($_GET['tab'])) {
    $tab = filter_input(INPUT_GET, 'tab', FILTER_DEFAULT);
} else {
    $tab = 'posts';
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
        if (mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM users WHERE id = $id;"))) {
            $user_profile = make_select_query($db_link, 
            "SELECT u.id, u.name, u.avatar_img, u.created_at, COUNT(p.id) AS posts_count, COUNT(DISTINCT s.id) AS subs 
                FROM users u JOIN posts p ON u.id = p.user_id
                LEFT JOIN subscriptions s ON u.id = s.to_user_id
                WHERE u.id = $id
            GROUP BY u.id", true);

            $subs = make_select_query($db_link,
            "SELECT COUNT(s.id) AS subs 
                FROM users u JOIN subscriptions s ON u.id = s.to_user_id
                WHERE u.id = $id
            GROUP BY u.id", true);

            $user_id = $user['id'];
            $is_subscribe = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM subscriptions WHERE user_id = $user_id AND to_user_id = $id"));

            switch ($tab) {
                case 'likes':
                    $content = make_select_query($db_link, 
                    "SELECT DISTINCT us.id AS user_id, us.name, us.avatar_img, pt.id AS post_id, pt.img, tp.icon_class AS type, l.created_at
                        FROM users us, posts pt JOIN types tp ON pt.post_type = tp.id
                        JOIN likes l ON pt.id = l.post_id
                        WHERE (us.id, pt.id) IN (SELECT u.id, p.id
                            FROM likes l JOIN users u ON l.user_id = u.id 
                            JOIN posts p ON p.id = l.post_id) 
                        AND pt.id IN (SELECT id FROM posts WHERE user_id = $id)
                        ORDER BY created_at DESC;");

                    break;
                case 'subscriptions':
                    $content = make_select_query($db_link, 
                    "SELECT u.id, u.name, u.avatar_img, u.created_at, COUNT(p.id) AS posts_count, COUNT(DISTINCT s.id) AS subs 
                        FROM users u JOIN posts p ON u.id = p.user_id
                        LEFT JOIN subscriptions s ON u.id = s.to_user_id
                        WHERE u.id IN (SELECT user_id FROM subscriptions WHERE to_user_id = $id)
                    GROUP BY u.id;");

                    $user_subscriptions = array_column(make_select_query($db_link,
                        "SELECT to_user_id FROM subscriptions WHERE user_id = $user_id;"), 'to_user_id');
                    
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
        }else {
            header("Location: /feed.php");
            exit();
        }
    }
}

$tab_content = include_template('profile-' . $tab . '.php', [
    'content' => $content, 
    'user_subscriptions' => $user_subscriptions
]);

$page_content = include_template('profile-main.php', [
    'content' => $tab_content,
    'user_profile' => $user_profile,
    'subs' => $subs,
    'is_subscribe' => $is_subscribe,
    'tab' => $tab,
    'tabs' => $tabs,
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