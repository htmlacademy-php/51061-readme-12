USE
    readme;
# список типов контента для поста;
INSERT INTO types
SET icon_class='post-photo',
    title='фото';#1
INSERT INTO types
SET icon_class='post-video',
    title='видео';#2
INSERT INTO types
SET icon_class='post-text',
    title='текст';#3
INSERT INTO types
SET icon_class='post-quote',
    title='цитата';#4
INSERT INTO types
SET icon_class='post-link',
    title='ссылка';
#5

# придумайте пару пользователей;
INSERT INTO users
SET email='larisa@gmail.com',
    login='Лариса',
    password='larisa123',
    avatar_url='userpic-larisa.jpg';#1
INSERT INTO users
SET email='petro@gmail.com',
    login='Петя',
    password='petro123',
    avatar_url='userpic-petro.jpg';#2
INSERT INTO users
SET email='tanya@gmail.com',
    login='Таня',
    password='tanya123',
    avatar_url='userpic-tanya.jpg';#3
INSERT INTO users
SET email='mark@gmail.com',
    login='Марк',
    password='mark123',
    avatar_url='userpic-mark.jpg';
#4

# существующий список постов.
INSERT INTO posts
SET content_type_id=4,
    author_id=1,
    title='Цитата',
    author_quote='Мы в жизни любим только раз, а после ищем лишь похожих';#1
INSERT INTO posts
SET content_type_id=3,
    author_id=3,
    title='Игра престолов!',
    text='Не могу дождаться начала финального сезона своего любимого сериала!';#2
INSERT INTO posts
SET content_type_id=3,
    author_id=2,
    title='Lorem 256!',
    text='Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore ut sunt voluptatibus neque magnam odio sint, obcaecati non facilis enim et aut quisquam explicabo, necessitatibus in. Voluptate aspernatur quidem suscipit assumenda, animi perspiciatis eaque doloremque odit placeat obcaecati temporibus sunt architecto eligendi earum doloribus!';#3
INSERT INTO posts
SET content_type_id=1,
    author_id=4,
    title='Наконец, обработал фотки!',
    image_url='rock-medium.jpg';#4
INSERT INTO posts
SET content_type_id=1,
    author_id=1,
    title='Моя мечта',
    image_url='coast-medium.jpg';#5
INSERT INTO posts
SET title='Лучшие курсы',
    content_type_id=5,
    author_id=4,
    url='www.htmlacademy.ru';
#6

# придумайте пару комментариев к разным постам;
INSERT INTO comments
SET post_id=6,
    author_id=2,
    content='Согласен, курсы что надо';
INSERT INTO comments
SET post_id=1,
    author_id=2,
    content='Ну-ну)';

# получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT p.title      AS title,
       t.icon_class AS type,
       u.login      AS user_name,
       u.avatar_url AS avatar,
       p.views
FROM posts p
         JOIN users u on p.author_id = u.id
         JOIN types t on p.content_type_id = t.id
ORDER BY views ASC;

# получить список постов для конкретного пользователя;
SELECT *
FROM posts
WHERE author_id = 1;

# получить список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT c.id         AS id,
       c.created_at AS created_at,
       content,
       u.login      AS login
FROM comments c
         JOIN users u on c.author_id = u.id
WHERE post_id = 6;

# добавить лайк к посту;
INSERT INTO likes
SET author_id=2,
    post_id=2;


# подписаться на пользователя.
INSERT INTO subscriptions
SET author_id=2,
    subscription=1;

SELECT *
from subscriptions s
WHERE s.author_id = 4
  AND s.subscription = 1

