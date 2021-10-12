CREATE
DATABASE IF NOT EXISTS readme
	DEFAULT CHARACTER SET utf8;
USE
readme;

/**
Пользователь
Представляет зарегистрированного пользователя.
Поля:
дата регистрации: дата и время, когда этот пользователь завёл аккаунт;
email;
логин;
пароль: хэшированный пароль пользователя;
аватар: ссылка на загруженный аватар пользователя;
*/
CREATE TABLE users
(
  id         INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  email      VARCHAR(320) NOT NULL,
  login      VARCHAR(128) NOT NULL,
  password   CHAR(255)    NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  avatar_url VARCHAR(2048)
);

/**
Сообщение
Одно сообщение из внутренней переписки пользователей на сайте
Поля:
дата создания: дата и время, когда это сообщение написали;
содержимое: задаётся пользователем.
Связи:
отправитель: пользователь, отправивший сообщение;
получатель: пользователь, которому отправили сообщение.
*/
CREATE TABLE messages
(
  id           INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  content      TEXT NOT NULL,
  sender_id    INT,
  recipient_id INT,
  FOREIGN KEY (sender_id) REFERENCES users (id),
  FOREIGN KEY (recipient_id) REFERENCES users (id)
);
/**
Тип контента
Один из пяти предопределенных типов контента.
Поля:
название (Текст, Цитата, Картинка, Видео, Ссылка);
имя класса для иконки (photo, video, text, quote, link);
*/
CREATE TABLE types
(
  id         INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  icon_class VARCHAR(10) NOT NULL,
  title      VARCHAR(10) NOT NULL
);
/**
Пост
Состоит из заголовка и содержимого. Набор полей, которые будут заполнены, зависит от выбранного типа.
Поля:
дата создания: дата и время, когда этот пост был создан пользователем;
заголовок: задаётся пользователем;
текстовое содержимое: задаётся пользователем;
автор цитаты: задаётся пользователем;
изображение: ссылка на сохранённый файл изображения;
видео: ссылка на видео с youtube;
ссылка: ссылка на сайт, задаётся пользователем;
число просмотров.
Связи:
автор: пользователь, создавший пост;
тип контента: тип контента, к которому относится пост;
хештеги: связь вида «многие-ко-многим» с сущностью «хештег».
*/
CREATE TABLE posts
(
  id              INT AUTO_INCREMENT PRIMARY KEY,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  title           VARCHAR(255) NOT NULL,
  text            TEXT,
  image_url       VARCHAR(2048),
  video_url       VARCHAR(2048),
  url             VARCHAR(2048),
  views           INT       DEFAULT 0,
  author_id       INT,
  content_type_id INT,
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (content_type_id) REFERENCES types (id)
);
/**
Комментарий
Текстовый комментарий, оставленный к одному из постов.
Поля:
дата создания: дата и время создания комментария;
содержимое: задается пользователем.
Связи:
автор: пользователь, создавший пост;
пост: пост, к которому добавлен комментарий.
*/
CREATE TABLE comments
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  content    TEXT,
  author_id  INT,
  post_id    INT,
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);
/**
Лайк
сущность состоит только из связей и не имеет собственных полей.
Связи:
пользователь: кто оставил этот лайк;
пост: какой пост лайкнули.
*/
CREATE TABLE likes
(
  id        INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT,
  post_id   INT,
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);
/**
Подписка
Эта сущность состоит только из связей и не имеет собственных полей. Сущность создается, когда пользователь подписывается на другого пользователя.
Связи:
автор: пользователь, который подписался;
подписка: пользователь, на которого подписались
*/
CREATE TABLE subscriptions
(
  author_id    INT,
  subscription INT,
  PRIMARY KEY (author_id, subscription),
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (subscription) REFERENCES users (id)
);
/**
Хештег
Один из используемых хештегов на сайте. Сущность состоит только из названия хештега.
*/
CREATE TABLE hashtags
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  name       VARCHAR(100)
);

CREATE TABLE post_hashtags
(
  post_id    INT,
  hashtag_id INT,
  PRIMARY KEY (post_id, hashtag_id),
  FOREIGN KEY (post_id) REFERENCES posts (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);

