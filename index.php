<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий'; // укажите здесь ваше имя
/*
$posts1 = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'value' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'value' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'value' => 'rock-medium.jpg',
        'user_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg'
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'value' => 'coast-medium.jpg',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'value' => 'www.htmlacademy.ru',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];
*/

$post_types = [];
$posts = [];

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");

    $post_types = make_select_query($db_link, "SELECT * FROM types;");

    $posts = make_select_query ($db_link, 
        "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class
        FROM posts p 
            JOIN users us ON p.user_id = us.id
            JOIN types tp ON p.post_type = tp.id
        ORDER BY show_count DESC LIMIT 6;");   
}

date_default_timezone_set("Asia/Yekaterinburg");

//add_time_to_post($posts);

$page_content = include_template('main.php', [
    'posts' => filter_posts($posts),
    'post_types' => $post_types
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'readme: популярное'
    ]);

print($layout_content);
?>