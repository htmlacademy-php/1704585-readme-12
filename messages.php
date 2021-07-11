<?php
require_once('helpers.php');
require_once('init.php');
require_once('mail.php');

$add_form = false;
$id = 0;
$errors = [];
$user_list = [];
$messages = [];
$content = '';

if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    die();
}
    
mysqli_set_charset($db_link, "utf8");

$user_id = $user['id'];

$user_list = make_select_query(
    $db_link,
    "SELECT id, name, avatar_img, content, published_at FROM users u JOIN 
        (SELECT user_id, content, published_at
	    FROM messages mes JOIN 
   	        (SELECT IF(from_user_id = $user_id, to_user_id, from_user_id) AS user_id, MAX(published_at) AS max_date
     	    FROM messages
		    WHERE from_user_id = $user_id OR to_user_id = $user_id
		    GROUP BY user_id
		    ) last_mes ON mes.published_at = last_mes.max_date 
                AND (mes.from_user_id = last_mes.user_id OR mes.to_user_id = last_mes.user_id)
	    ) AS m ON m.user_id = id 
    WHERE id IN (SELECT from_user_id FROM messages WHERE to_user_id = $user_id)
        OR id IN (SELECT to_user_id FROM messages WHERE from_user_id = $user_id);"
);
    
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $user_list_ids = array_column($user_list, 'id');
    if (!in_array($id, $user_list_ids)) {
        $user_list = make_select_query(
            $db_link,
            "SELECT id, name, avatar_img FROM users 
            WHERE id IN (SELECT from_user_id FROM messages WHERE to_user_id = $user_id)
                OR id IN (SELECT to_user_id FROM messages WHERE from_user_id = $user_id)
                OR id = $id;"
        );
    }
} else {
    $id = $user_list[0]['id'];
}

if ($user_list) {
    $messages = make_select_query(
        $db_link,
        "SELECT u.id, from_user_id, name, avatar_img, content, published_at 
    FROM users u JOIN messages m ON u.id = m.from_user_id
        WHERE m.to_user_id = $id
    UNION
    SELECT u.id, from_user_id, name, avatar_img, content, published_at 
    FROM users u JOIN messages m ON u.id = m.from_user_id
        WHERE m.to_user_id = $user_id AND u.id = $id
    ORDER BY published_at ASC;"
    );
} else {
    $messages = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['text'])) {
        $errors['text'] = 'Это поле обязательно к заполнению';
    } else {
        $errors = [];

        $content = trim(filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING));

        $sql = "INSERT INTO messages (content, from_user_id, to_user_id) VALUES (?, $user_id, $id);";
        $stmt = db_get_prepare_stmt($db_link, $sql, [$content]);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            print("Ошибка запроса: " . mysqli_error($db_link));
            die();
        }
            
        header("Location: messages.php?id=" . $id);
    }
}

$page_content = include_template('messages-main.php', [
    'user_list' => filter_posts($user_list),
    'messages' => filter_posts($messages),
    'content' => $content,
    'errors' => $errors,
    'auth_user' => $user,
    'id' => $id
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: личные сообщения',
    'user' => $user,
    'page' => 'messages',
    'header_my_nav' => $header_my_nav,
    'is_auth' => $is_auth,
    'add_form' => $add_form
]);

print($layout_content);
