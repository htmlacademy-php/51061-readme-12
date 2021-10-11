<?php

require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Aндрей';
$title = 'readme: популярное';

$posts = [
    [
        'title' => 'Цитата',
        "type" => 'post-quote',
        "content" => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        "user_name" => 'Лариса',
        "avatar" => 'userpic-larisa-small.jpg',
    ],
    [
        "title" => 'Игра престолов',
        "type" => 'post-text',
        "content" => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        "user_name" => 'Владик',
        "avatar" => 'userpic.jpg',
    ],
    [
        "title" => 'Тест',
        "type" => 'post-text',
        "content" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore ut sunt voluptatibus neque magnam odio sint, obcaecati non facilis enim et aut quisquam explicabo, necessitatibus in. Voluptate aspernatur quidem suscipit assumenda, animi perspiciatis eaque doloremque odit placeat obcaecati temporibus sunt architecto eligendi earum doloribus, ipsa libero, quasi esse asperiores quaerat! Fuga sed dolores voluptate cumque, laboriosam dolor, quae corporis iste in dolorem quam quisquam omnis soluta harum deleniti! Quos magni voluptas velit, porro sit praesentium vel ratione fugit repellat laboriosam eos id cumque officia quibusdam perferendis ea aliquam mollitia aut totam accusamus vero voluptate consequuntur architecto. Harum, commodi in! Ullam iure earum laboriosam minus tempora alias doloribus dicta reprehenderit? Veritatis est minima ipsam eum dolore voluptatem maxime ratione, sed delectus totam unde fugit ipsum cumque laboriosam dicta tempora atque perferendis id adipisci cupiditate optio eos modi vel mollitia. Laudantium quam saepe aliquam ad voluptates inventore soluta nulla dolores nobis! Recusandae optio pariatur at ipsam itaque exercitationem, voluptatibus neque voluptates repellendus odit. Velit amet sed tenetur saepe aut quod cupiditate eos perspiciatis aspernatur laudantium minus, officiis magni. Eius, dignissimos? Nemo, nihil sit? Quisquam accusantium corrupti nihil aliquam, deleniti quasi velit, distinctio doloribus, asperiores hic neque quam explicabo ea. Obcaecati harum, aperiam debitis fuga placeat a non nesciunt nisi ratione esse cum quis! Quaerat omnis harum officia. Fuga voluptatum minima esse tempora quae hic totam eaque quaerat non impedit, excepturi voluptates, architecto nihil vitae necessitatibus ex quisquam repudiandae tenetur neque! Dolorum ullam consequatur corporis atque nobis illo illum voluptas ducimus ab, iusto inventore nostrum ex commodi officia sint? Tempore veniam dolorem, ducimus non dolores fugit distinctio esse corporis laboriosam ex ullam quis fuga aliquam alias porro nesciunt! Soluta culpa consectetur quisquam aspernatur aliquam illum dolor rerum numquam facere libero labore, dolores voluptatum vel deleniti, veniam id aperiam eius provident expedita ab minima.',
        "user_name" => 'Лукавый',
        "avatar" => 'userpic.jpg',
    ],
    [
        "title" => 'Наконец, обработал фотки!',
        "type" => 'post-photo',
        "content" => 'rock-medium.jpg',
        "user_name" => 'Виктор',
        "avatar" => 'userpic-mark.jpg',
    ],
    [
        "title" => 'Моя мечта',
        "type" => 'post-photo',
        "content" => 'coast-medium.jpg',
        "user_name" => 'Лариса',
        "avatar" => 'userpic-larisa-small.jpg',
    ],
    [
        "title" => 'Лучшие курсы',
        "type" => 'post-link',
        "content" => 'www.htmlacademy.ru',
        "user_name" => 'Владик',
        "avatar" => 'userpic.jpg',
    ]
];

$content = include_template('main.php', ["posts" => $posts]);
$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name"));

print($page);
?>

