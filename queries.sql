/*
Вносим список типов контента для поста
*/
INSERT INTO types (type_name, icon_class) VALUES
    ('post-text', 'icon-text'), 
    ('post-photo', 'icon-photo'),
    ('post-link', 'icon-link'), 
    ('post-quote', 'icon-quote'), 
    ('post-video', 'icon-video');

/*
Создаем несколько пользователей
*/
INSERT INTO users 
SET name = 'Владик', email = 'vlad@gmail.com', password = 'qwerty', avatar_img = 'userpic.jpg';

INSERT INTO users 
SET NAME = 'Лариса', email = 'lara.rogova@gmail.com', password = 'asdfg', avatar_img = 'userpic-larisa-small.jpg';

INSERT INTO users 
SET NAME = 'Виктор', email = 'vitek@mail.ru', password = 'asdfg', avatar_img = 'userpic-mark.jpg';

/*
Добавляет пост с цитатой в список постов
*/
INSERT INTO posts (title, content, user_id, post_type, show_count)
VALUES
(
    'Цитата',
    'Мы в жизни любим только раз, а после ищем лишь похожих',
    (SELECT id FROM users WHERE name = 'Лариса'),
    (SELECT id FROM types WHERE type_name = 'post-quote'),
    0
);

/*
Добавляет пост с текстом в список постов
*/
INSERT INTO posts (title, content, user_id, post_type, show_count)
VALUES
(
    'Игра престолов',
    'Не могу дождаться начала финального сезона своего любимого сериала!',
    (SELECT id FROM users WHERE name = 'Владик'),
    (SELECT id FROM types WHERE type_name = 'post-text'),
    0
);

/*
Добавляет посты с фото в список постов
*/
INSERT INTO posts (title, img, user_id, post_type, show_count)
VALUES
(
    'Наконец, обработал фотки!',
    'rock-medium.jpg',
    (SELECT id FROM users WHERE name = 'Виктор'),
    (SELECT id FROM types WHERE type_name = 'post-photo'),
    0
),
(
    'Моя мечта',
    'coast-medium.jpg',
    (SELECT id FROM users WHERE name = 'Лариса'),
    (SELECT id FROM types WHERE type_name = 'post-photo'),
    0
);

/*
Добавляет пост с ссылкой в список постов
*/
INSERT INTO posts (title, link, user_id, post_type, show_count)
VALUES
(
    'Лучшие курсы',
    'www.htmlacademy.ru',
    (SELECT id FROM users WHERE name = 'Владик'),
    (SELECT id FROM types WHERE type_name = 'post-link'),
    0
);

/*
Добавляет комментарии к постам
*/
INSERT INTO comments (comment, user_id, post_id)
VALUES
(
    'Мечты сбываются!',
    (SELECT id FROM users WHERE name = 'Владик'),
    (SELECT id FROM posts WHERE title = 'Моя мечта')
),
(
    'Замечательное место для зимнего отдыха!',
    (SELECT id FROM users WHERE name = 'Виктор'),
    (SELECT id FROM posts WHERE title = 'Моя мечта')
);

/*
Получает список постов с сортировкой по популярности с именами авторов и типом контента
*/
SELECT title, content, img, link, show_count, published_at, name, type_name 
FROM posts p 
    JOIN users us ON p.user_id = us.id
    JOIN types tp ON p.post_type = tp.id
ORDER BY show_count DESC;

/*
Получает список постов для конкретного пользователя
*/
SELECT * FROM posts WHERE user_id = (SELECT id FROM users WHERE NAME = 'Владик');

/*
Получает список комментариев для поста с указанием логина пользователя
*/
SELECT comment, name 
FROM comments com JOIN users us ON com.user_id = us.id;

/*
Добавляет лайк к посту
*/
INSERT INTO likes (user_id, post_id)
VALUES
(
	(SELECT id FROM users WHERE name = 'Владик'),
	(SELECT id FROM posts WHERE title = 'Наконец, обработал фотки!')
);

/*
Подписка на пользователя
*/
INSERT INTO subscriptions (user_id, to_user_id)
VALUES
(
	(SELECT id FROM users WHERE name = 'Виктор'),
	(SELECT id FROM users WHERE name = 'Лариса')
);