<?php
// Время выполнения - 25 минут.
// Заголовок страницы.
$metaTitle = 'Авторизация';

// Пользователь нажал на кнопку авторизации:
if ($_POST['login'] === '1') {
    // Присваиваю введенные пользователем данные.
    $user = $_POST;
    // Собираю ошибки валидации.
    $errorMessage = validateUser($user);

    // Маршрут до страницы авторизации.
    $route = '/auth/login';
    if (!\is_null($errorMessage)) {
        // Помещаю в сессию все ошибки валидации.
        $_SESSION['errors'] = $errorMessage;
    } else {
        // Экранирую данные.
        $user = escapeData($user);
        // Получаю данные пользователя по введенной почте.
        $userDataBase = getUser($user['email']);
        // Проверяю совпадает ли введенный и существующий пароль в базе данных.
        if (!\password_verify($user['password'], $userDataBase['hash'])) {
            $_SESSION['errors'] = 'Вы ввели неверные данные.' . "\n";
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
    \header("Location: {$route}");
}

// Путь до страницы с регистрацией.
$content = render($currentAction['view']);