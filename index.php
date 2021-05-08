<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий'; // укажите здесь ваше имя
$posts = [
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

function cut_string ($string, $length = 300) {
    $words = explode(" ", $string);
    $result_string = "<p>";
    if (mb_strlen($string) > $length){
        $i = 0;
        $current_length = -1;
        $result_array = [];
        do {
            $current_length += mb_strlen($words[$i]) + 1;
            if ($current_length < $length) {
                array_push($result_array, $words[$i]);
            }
            $i++;
        } while ($current_length < $length);
        $result_string .= rtrim(implode(" ", $result_array), " .,?!:;") . "...</p>";
        $result_string .= '<a class="post-text__more-link" href="#">Читать далее</a>';
    } else {
        $result_string .= $string . "</p>";
    }
    
    return $result_string;
}

function filter_data ($string) {
    htmlspecialchars($string);
    //$text = strip_tags($string);
    return $text;
}

function filter_posts ($posts) {
    $new_posts = [];
    foreach ($posts as $post) {
        $new_post = [];
        foreach ($post as $key => $string) {
            if (is_string($string)) {
                $new_post[$key] = filter_data($string);
            }
        }
        array_push($new_posts , $new_post);
    }
    return $new_posts;
}

$page_content = include_template('main.php', ['posts' => filter_posts($posts)]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'readme: популярное'
    ]);

print($layout_content);
?>