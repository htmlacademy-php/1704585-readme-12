<?php
require_once('helpers.php');
require_once('init.php');
require_once('mail.php');

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
        $check_id = mysqli_num_rows(mysqli_query($db_link, "SELECT id FROM users WHERE id = $id"));
        
        if ($check_id) {
            $subscription = ['0' => $user['id'], '1' => $id];
            $sql = "INSERT INTO subscriptions (user_id, to_user_id) VALUES (?, ?);";

            $stmt = db_get_prepare_stmt($db_link, $sql, $subscription);

            $result = mysqli_stmt_execute($stmt);
            if (!$result) {
                print("Ошибка запроса: " . mysqli_error($db_link));
            } else {
                $to_user = make_select_query($db_link, "SELECT name, email FROM users WHERE id = $id;", true);

                $message_content = "Здравствуйте, " . $to_user['name'] . ". На вас подписался новый пользователь " . $user['name'] . ". Вот ссылка на его профиль: ";

                $message = new Swift_Message();
                $message->setSubject("У вас новый подписчик");
                $message->setTo([$to_user['email'] => $to_user['name']]);
                $message->setBody($message_content . '<a href="http://localhost/profile.php?id=' . $user['id'] . '">' . $user['name'] .'</a>', 'text/html');
                $message->setFrom(['keks@phpdemo.ru' => 'ReadMe']);

                $result = $mailer->send($message);
            }
        }
    }

    header("Location: profile.php?id=" . $id);
}
