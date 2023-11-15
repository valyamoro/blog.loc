<?php

if (empty($_SESSION['user'])) {
    $_SESSION['warning'] = 'Вы не авторизованы.' . "\n";
    \header('Location /');
    die;
}

$metaTitle = 'Изменить данные поста';

$id = $_GET['id_post'];
$post = getPost($id);

if ($post['user_id'] !== $_SESSION['user']['id']) {
    \header('Location: /');
}

if (!empty($_POST)) {
    $route = '/';

    $post = $_POST;

    $post['image_path'] = uploadFile('posts', $_FILES['image']);

    $post['is_active'] ?? $post['is_active'] = 0;

    (editPost($post, $id)) === 0 ?: $_SESSION['errors'] = 'Произошла ошибка, попробуйте еще раз!' . "\n";
    if (!empty($_SESSION['errors'])) {
        $route = '/blog/edit_post';
    }

    \header("Location: {$route}");
    die;
}


$content = render($currentAction['view']);
