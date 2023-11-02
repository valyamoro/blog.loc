<?php

// Заголовок страницы.
$metaTitle = 'Главная страница';

// Панель управления страницами.
$html = null;

// Кол-во постов на одной странице.
$limit = 2;

// Позиция поста в базе данных.
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

// Нужное кол-во постов.
$posts = getAllPosts($start, $limit);

// Получаю кол-во всех постов в базе данных.
$items = getCountPosts();

// Кол-во всех страниц.
$pageCount = ceil($items / $limit);

// Отправляем на главную страницу нужные данные.
$content = render($currentAction['view'], [
    'html' => $html,
    'limit' => $limit,
    'posts' => $posts,
    'items' => $items,
    'pageCount' => $pageCount,
]);
