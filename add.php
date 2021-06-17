<?php
require_once('helpers.php');
require_once('init.php');
require_once('mail.php');

$add_form = true;
$user_id = $user['id'];

$id = '1';
$post_types = [];
$errors = [];
$post = [];

$db_link = mysqli_connect("127.0.0.1", "root", "root", "readme");
if ($db_link == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    mysqli_set_charset($db_link, "utf8");
    
    $post_types = make_select_query($db_link, "SELECT * FROM types;");

    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = '1';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post = $_POST;
        
        $required_fields = ['title', 'text', 'author', 'link', 'video'];
        $rules = [
            'title' => function ($value) {
                return validateFilled($value, 'Заголовок');
            },
            'text' => function ($value) {
                return validateFilled($value, 'Текст');
            },
            'quote' => function ($value) {
                return validateFilledLength($value, 70, "Цитата");
            },
            'author' => function ($value) {
                return validateFilled($value, 'Автор');
            },
            'link' => function ($value) {
                return validateUrl($value, 'Ссылка');
            },
            'video' => function ($value) {
                return validateUrl($value, 'Ссылка Youtube', true);
            }
        ];
    
        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($value);
            }
        }
            
        if ($id === '2') {
            $avalable_file_types = ['image/jpeg', 'image/gif', 'image/png'];
            if (!empty($_FILES['photo']['name'])) {
                $tmp_name = $_FILES['photo']['tmp_name'];
                $img_name = $_FILES['photo']['name'];
    
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_type = finfo_file($finfo, $tmp_name);
                $valid_type = validateFileType($file_type, $avalable_file_types);
                if(!$valid_type) {
                    move_uploaded_file($tmp_name, 'uploads/' . $img_name);
                    $post['img'] = $img_name;
                }
                else {
                    $errors['file'] = $valid_type;
                }
            } elseif (empty($_POST['url'])) {
                $errors['url'] = "Добавьте файл или введите ссылку.";
            } elseif (validateUrl($_POST['url'])) {
                $file_type = getFileType($_POST['url']);
                $file = file_get_contents($_POST['url']);
                if ($file) {
                    $valid_type = validateFileType($file_type, $avalable_file_types);
                    if (!$valid_type) {
                        $img_name = pathinfo($_POST['url'], PATHINFO_BASENAME);
                        file_put_contents('uploads/' . $img_name, $file);
                        $post['img'] = $img_name;
                    }
                    else {
                        $errors['file'] = $valid_type;
                    }
                }
            }
        }

        $tags = [];

        if(isset($post['tags']) && !empty($post['tags'])) {
            $tags = explode(' ', $post['tags']);
            $check_tags = validate_tags($tags);
            if ($check_tags) {
                $errors['tags'] = $check_tags;
            }
        }

        $errors = array_filter($errors);
        
        if (!count($errors)) {
            
            if (isset($post['text'])) {
                $post['content'] = $post['text'];
            }
            if (isset($post['quote'])) {
                $post['content'] = $post['quote'];
            }
            $post = array_filter($post);

            $post = fillArray($post, ['title', 'content', 'author', 'img', 'video', 'link']);

            $sql = "INSERT INTO posts (title, content, author, img, video, link, user_id, post_type) 
                VALUES (?, ?, ?, ?, ?, ?, $user_id, $id);";

            $stmt = db_get_prepare_stmt($db_link, $sql, $post);
            
            $result = mysqli_stmt_execute($stmt);
            if($result) {
                $post_id = mysqli_insert_id($db_link);
                
                foreach ($tags as $key => $tag) {
                    $check_tag = make_select_query($db_link, "SELECT id FROM hashtags WHERE hash_name = '$tag'", true);
                    
                    if (!$check_tag) {
                        $sql = "INSERT INTO hashtags (hash_name) VALUES (?);";
                        $insert_tag = [$tag];

                        $stmt = db_get_prepare_stmt($db_link, $sql, $insert_tag);
                        $result = mysqli_stmt_execute($stmt);
                        if($result) {
                            $tag_id = mysqli_insert_id($db_link);
                        } else {
                            print("Ошибка запроса: " . mysqli_error($db_link));
                        }
                    } else {
                        $tag_id = $check_tag['id'];
                    }

                    $sql = "INSERT INTO posts_hashtags (post_id, hash_id) VALUES (?, ?);";
                    $insert_post_tag = [$post_id, $tag_id];

                    $stmt = db_get_prepare_stmt($db_link, $sql, $insert_post_tag);
                    $result = mysqli_stmt_execute($stmt);
                    if (!$result) {
                        print("Ошибка запроса: " . mysqli_error($db_link));
                    }
                }

                $subscribed_users = make_select_query($db_link, 
                    "SELECT name, email FROM users WHERE id IN (SELECT user_id FROM subscriptions WHERE to_user_id = $user_id);");
                               
                foreach ($subscribed_users as $subscribed_user) {
                    $message_content = "Здравствуйте, " . $subscribed_user['name'] . ". Пользователь " . $user['name'] . " только что опубликовал новую запись \"" . $post['title'] . "\" . Посмотрите её на странице пользователя: ";
                    $message_subject = "Новая публикация от пользователя " . $user['name'];

                    $message = new Swift_Message();
                    $message->setSubject($message_subject);
                    $message->setTo([$subscribed_user['email'] => $subscribed_user['name']]);
                    $message->setBody($message_content . '<a href="http://localhost/profile.php?id=' . $user['id'] . '">' . $user['name'] .'</a>', 'text/html');
                    $message->setFrom(['keks@phpdemo.ru' => 'ReadMe']);

                    $send_result = $mailer->send($message);
                }
                
                header("Location: post.php?id=" . $post_id);
            } else {
                print("Ошибка запроса: " . mysqli_error($db_link));
            }
        }
    }
}

$add_file = "add-" . $post_types[$id - 1]['icon_class'] . ".php";

$add_content = include_template($add_file, [
    'errors' => $errors
    ]);
$page_content = include_template('adding-post.php', [
    'content' => $add_content,
    'post_types' => $post_types,
    'errors' => $errors,
    'id' => $id
    ]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user' => $user,
    'header_my_nav' => $header_my_nav,
    'title' => 'readme: добавление публикации',
    'add_form' => $add_form
    ]);

print($layout_content);
?>