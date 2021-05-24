<?php
require_once('helpers.php');

$is_auth = 1;
$user_name = "Дмитрий";
$add_form = true;
$user_id = 4;

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
        
        $required_fields = ['title', 'text', 'author', 'link', 'video_url'];
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
            'video_url' => function ($value) {
                return validateUrl($value, 'Ссылка Youtube', true);
            }
        ];
    
        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($key);
            }
    
        }
            
        if ($id === '2') {
            if (!empty($_FILES['photo']['name'])) {
                $tmp_name = $_FILES['photo']['tmp_name'];
                $img_name = $_FILES['photo']['name'];
    
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_type = finfo_file($finfo, $tmp_name);
                if(!validateFileType($file_type)) {
                    move_uploaded_file($tmp_name, 'uploads/' . $img_name);
                    $post['img'] = $img_name;
                }
            } elseif (empty($_POST['url'])) {
                $errors['url'] = "Добавьте файл или введите ссылку.";
            } elseif (validateUrl($_POST['url'])) {
                $file_type = getFileType($_POST['url']);
                $file = file_get_contents($_POST['url']);
                if ($file) {
                    if (!validateFileType($file_type)) {
                        $img_name = pathinfo($_POST['url'], PATHINFO_BASENAME);
                        file_put_contents('uploads/' . $img_name, $file);
                        $post['img'] = $img_name;
                    }
                }
            }
        }

        $errors = array_filter($errors);
        
        if (!count($errors)) {
            if(isset($post['tags'])) {
                $tags = $post['tags'];
            }

            if (isset($post['text'])) {
                $post['content'] = $post['text'];
                $post['text'] = null;
            }
            if (isset($post['quote'])) {
                $post['content'] = $post['quote'];
                $post['quote'] = null;
            }
            $post = array_filter($post);

            $post = fillArray($post, ['title', 'content', 'author', 'img', 'link']);

            $sql = "INSERT INTO posts (title, content, author, img, video, link, user_id, post_type) 
                VALUES (?, ?, ?, ?, ?, ?, $user_id, $id);";
            $stmt = db_get_prepare_stmt($db_link, $sql, $post);

            $result = mysqli_stmt_execute($stmt);
            if($result) {
                $post_id = mysqli_insert_id($db_link);

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
    'user_name' => $user_name,
    'title' => 'readme: добавление публикации',
    'add_form' => $add_form
    ]);

print($layout_content);
?>