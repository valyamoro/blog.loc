<?php

// Заголовок страницы.
$metaTitle = 'Авторизация';

// Пользователь нажал на кнопку авторизации:
if ($_POST['login'] == 1) {
    // Присваиваю введенные пользователем данные.
    $user = $_POST;
    // Собираю ошибки валидации.
    $errorMessage = validateUser($user);

    // Маршрут до страницы авторизации.
    $route = 'auth/login';
    if (!\is_null($errorMessage)) {
        // Помещаю в сессию все ошибки валидации.
        $_SESSION['errors'] = $errorMessage;
    } else {
        // Экранирую данные.
        $user = escapeData($user);
        // Получаю данные пользователя по введенной почте.
        $userDataBase = getUser($user['email']);

        // Проверяю совпадает ли введенный и существующий пароль в базе данных.
        if ($userDataBase['password'] !== $user['password']) {
            $_SESSION['errors'] = 'Вы ввели неверные данные.';
        } else {
            // Помещаю в сессию данные пользователя.
            $_SESSION['user'] = [
                'id' => $userDataBase['id'],
                'name' => $userDataBase['username'],
                'email' => $userDataBase['email'],
            ];
            // Маршрут до страницы профиля.
            $route = '/auth/profile';
        }
    }
    // Перенаправляю на нужную страницу.
    header("Location: {$route}");
}

$content = render($currentAction['view']);