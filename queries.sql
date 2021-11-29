USE
  readme;
# список типов контента для поста;
INSERT INTO types SET
  icon_class='post-photo',
  title='фото';#1
INSERT INTO types SET
  icon_class='post-video',
  title='видео';#2
INSERT INTO types SET
  icon_class='post-text',
  title='текст';#3
INSERT INTO types SET
  icon_class='post-quote',
  title='цитата';#4
INSERT INTO types SET
  icon_class='post-link',
  title='ссылка';#5

# придумайте пару пользователей;
INSERT INTO users SET
    email='larisa@gmail.com',
    login='Лариса',
    password='larisa123',
    avatar_url='userpic-larisa.jpg';#1
INSERT INTO users SET
    email='petro@gmail.com',
    login='Петя',
    password='petro123',
    avatar_url='userpic-petro.jpg';#2
INSERT INTO users SET
    email='tanya@gmail.com',
    login='Таня',
    password='tanya123',
    avatar_url='userpic-tanya.jpg';#3
INSERT INTO users SET
    email='mark@gmail.com',
    login='Марк',
    password='mark123',
    avatar_url='userpic-mark.jpg';#4

# существующий список постов.
INSERT INTO posts SET
    content_type_id=4,
    author_id=1,
    title='Цитата',
    content='Мы в жизни любим только раз, а после ищем лишь похожих';#1
INSERT INTO posts SET
    content_type_id=3,
    author_id=3,
    title='Игра престолов!',
    content='Не могу дождаться начала финального сезона своего любимого сериала!';#2
INSERT INTO posts SET
    content_type_id=3,
    author_id=2,
    title='Lorem 256!',
    content='Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore ut sunt voluptatibus neque magnam odio sint, obcaecati non facilis enim et aut quisquam explicabo, necessitatibus in. Voluptate aspernatur quidem suscipit assumenda, animi perspiciatis eaque doloremque odit placeat obcaecati temporibus sunt architecto eligendi earum doloribus!';#3
INSERT INTO posts SET
    content_type_id=1,
    author_id=4,
    title='Наконец, обработал фотки!',
    image_url='rock-medium.jpg';#4
INSERT INTO posts SET
    content_type_id=1,
    author_id=1,
    title='Моя мечта',
    image_url='coast-medium.jpg';#5
INSERT INTO posts SET
    content_type_id=5,
    author_id=4,
    url='www.htmlacademy.ru';#6

# придумайте пару комментариев к разным постам;
INSERT INTO comments SET
    post_id=6,
    author_id=2,
    content='Согласен, курсы что надо';
INSERT INTO comments SET
    post_id=1,
    author_id=2,
    content='Ну-ну)';
