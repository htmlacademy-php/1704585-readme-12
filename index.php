<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий'; // укажите здесь ваше имя

$sort_types = [
    [
        'id' => 1,
        'title' => 'Популярность',
        'order_by' => 'show_count'
    ],
    [
        'id' => 2,
        'title' => 'Лайки',
        'order_by' => 'likes'
    ],
    [
        'id' => 3,
        'title' => 'Дата',
        'order_by' => 'published_at'
    ] 
];

$post_types = [];
$posts = [];
$id = 0;
$sort = 1;

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");
    
    $post_types = make_select_query($db_link, "SELECT * FROM types;");

    $id = filter_input(INPUT_GET, 'id');
    if (isset($_GET['order_by'])) {
        $sort = filter_input(INPUT_GET, 'order_by');
    }
    $condition = "";

    if ($id) {
        $condition = "WHERE tp.id = $id";
    }
    $posts = make_select_query ($db_link, 
        "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(l.id) AS likes
        FROM posts p 
            JOIN users us ON p.user_id = us.id
            JOIN types tp ON p.post_type = tp.id 
            LEFT JOIN likes l ON l.post_id = p.id " .
        $condition .
        " GROUP BY p.id ORDER BY " . $sort_types[$sort - 1]['order_by'] . " DESC LIMIT 6;");
}

date_default_timezone_set("Asia/Yekaterinburg");

$page_content = include_template('main.php', [
    'posts' => filter_posts($posts),
    'post_types' => $post_types,
    'sort_types' => $sort_types,
    'id' => $id,
    'sort' => $sort
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'readme: популярное'
    ]);

print($layout_content);
?>