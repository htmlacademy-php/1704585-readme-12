<?php
require_once('helpers.php');
require_once('init.php');

$add_form = false;

$sort_types = [
    [
        'id' => '1',
        'title' => 'Популярность',
        'order_by' => 'show_count'
    ],
    [
        'id' => '2',
        'title' => 'Лайки',
        'order_by' => 'likes'
    ],
    [
        'id' => '3',
        'title' => 'Дата',
        'order_by' => 'published_at'
    ]
];

$post_types = [];
$posts = [];
$id = 0;
$sort = 1;

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
    if (isset($_GET['order_by'])) {
        $sort = filter_input(INPUT_GET, 'order_by', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $sort = '1';
    }

    if (isset($_GET['page'])) {
        $current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $current_page = '1';
    }

    if ($id) {
        $condition = "WHERE tp.id = $id";
    } else {
        $condition = '';
    }
    $total_items = make_select_query(
        $db_link,
        "SELECT COUNT(p.id) AS total FROM posts p 
        JOIN types tp ON p.post_type = tp.id " . $condition,
        true
    )['total'];

    $page_items = 6;
    $pages_count = ceil($total_items / $page_items);

    if ($current_page) {
        if ($current_page > $pages_count) {
            $current_page = $pages_count;
        }
        $offset = ($current_page - 1) * $page_items;
    } else {
        $current_page = '1';
        $offset = '0';
    }
    
    if ($total_items) {
        $posts = make_select_query(
            $db_link,
            "SELECT p.*, name, avatar_img AS avatar, type_name AS type, icon_class AS class, COUNT(DISTINCT c.id) AS comments, COUNT(l.id) AS likes
            FROM posts p 
                JOIN users us ON p.user_id = us.id
                JOIN types tp ON p.post_type = tp.id 
                LEFT JOIN comments c ON p.id = c.post_id
                LEFT JOIN likes l ON l.post_id = p.id " .
            $condition .
            " GROUP BY p.id ORDER BY " . $sort_types[$sort - 1]['order_by'] . " DESC LIMIT " . $page_items . " OFFSET " . $offset
        );
    }
}

date_default_timezone_set("Asia/Yekaterinburg");

$page_content = include_template('main.php', [
    'posts' => filter_posts($posts),
    'pages_count' => $pages_count,
    'current_page' => $current_page,
    'post_types' => $post_types,
    'sort_types' => $sort_types,
    'id' => $id,
    'sort' => $sort
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user' => $user,
    'page' => 'popular',
    'header_my_nav' => $header_my_nav,
    'title' => 'readme: популярное',
    'add_form' => $add_form
    ]);

print($layout_content);
