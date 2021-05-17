<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Функция обрезает длинну строки и возвращает итоговый HTML абзац
 * @param string $string входящая строка
 * @param $length максимальная длинна строки для обрезки, по умолчанию 300 символов
 * @return string итоговый HTML абзац
 */
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

/**
 * Фунуция фильтрует пользовательский архив с постами заменяя HTML-теги на HTML-мнемоники
 * @param array $post массив с пользавательскими постами
 * @return array массив с отфильтрованными данными
 */
function filter_posts ($posts) {
    $new_posts = [];
    foreach ($posts as $post) {
        $new_post = [];
        foreach ($post as $key => $string) {
            if (is_string($string)) {
                $new_post[$key] = htmlspecialchars($string);
            }
        }
        array_push($new_posts , $new_post);
    }
    return $new_posts;
}

/**
 * Функция добавляет дату к постам пользователей
 * @param array &$posts ссылки на массив с постами пользователей
 */
function add_time_to_post (&$posts) {
    foreach ($posts as $key => &$post) {
        $post['datetime'] = generate_random_date($key);
    }
}

/**
 * Функция преобразовывает дату в относительный формат. Заменяет дату на сообщение сколько времени назад произошло событие
 * @param string $datetime входящая дата
 * @return string результирующая строка сообщения
 */
function make_datetime_relative ($datetime) {
    $ts_input = strtotime($datetime);
    $ts_now = time();
    $count_minutes = ceil(($ts_now - $ts_input) / 60);

    $string = "";
    $count = 0;

    switch ($count_minutes) {
        case $count_minutes < 60: 
            $count = $count_minutes;
            $string = $count . get_noun_plural_form($count, " минута", " минуты", " минут");
            break;
        case $count_minutes < 60 * 24:
            $count = ceil($count_minutes / 60);
            $string = $count . get_noun_plural_form($count, " час", " часа", " часов");
            break;
        case $count_minutes < 60 * 24 * 7:
            $count = ceil($count_minutes / 60 / 24);
            $string = $count . get_noun_plural_form($count, " день", " дня", " дней");
            break;
        case $count_minutes < 60 * 24 * 7 * 5:
            $count = ceil($count_minutes / 60 / 24 / 7);
            $string = $count . get_noun_plural_form($count, " неделя", " недели", " недель");
            break;
        default:
            $count = ceil($count_minutes / 60 / 24 / 31);
            $string = $count . get_noun_plural_form($count, " месяц", " месяца", " месяцев");
            break;
    }

    $string .= " назад";
    return $string;
}

/**
 * Функция выполняет запрос SELECT и возвращает из базы готовый массив с запрошенными данными
 * @param link подключение к базе данных
 * @param string строка запроса на выборку данных
 * @return array готовый массив с данными
 */
function make_select_query ($db_link, $sql) {
    $result = mysqli_query($db_link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка запроса: " . mysqli_error($db_link));
    }
}