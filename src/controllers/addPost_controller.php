<?php
// Заголовок страницы.
$metaTitle = 'Добавить пост';

// Если пользователь нажал на кнопку "Добавить пост".
if (!empty($_POST)) {
    // Данные поста.
    $post = $_POST;
    // Данные изображения поста.
    $image = $_FILES;

    // Строка с ошибками валидации поста.
    $errorMessage = validatePost($post);
    // Строка с ошибками валидации изображения поста.
    $errorMessageImage = validatePostImage($image);

    // Маршрут до страницы с добавлением поста.
    $route = '/blog/add_post';
    // Если есть ошибки:
    if (!\is_null($errorMessage) || !\is_null($errorMessageImage)) {
        // Помещаю в сессию ошибки.
        $_SESSION['errors'] = $errorMessage . $errorMessageImage;
    } else {
        // Добавляю в данные поста айди текущего пользователя.
        $post['user_id'] = $_SESSION['user']['id'];
        // Добавляю в данные поста путь до изображения.
        $post['image'] = uploadImage($image);
        // Если по какой-то причине пути нету.
        if (empty($post['image'])) {
            $_SESSION['errors'] = 'Что-то пошло не так!' . "\n";
        } else {
            // Если новый пост добавился, то формирую путь до главной страницы, иначе сообщение с ошибкой.
            addPost($post) ? $route = '/' : $_SESSION['errors'] = 'Что-то пошло не так!' . "\n";
        }
        // Перенаправляю на нужную страницу.
        \header("Location: {$route}");
    }
}

// Путь до текущего представления.
$content = render($currentAction['view']);
