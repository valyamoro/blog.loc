<?php
// 27.10.2023 - Рефакторинг: 43 +

// Заголовок страницы.
$metaTitle = 'Регистрация';

// Пользователь нажал на кнопку регистрации:
if ($_POST['registry'] === '1') {
    // Присваиваю введенные пользователем данные.
    $user = $_POST;
    // Собираю ошибки валидации.
    $errorMessage = validateUser($user);
    if (!\is_null($errorMessage)) {
        // Помещаем в сессию все ошибки валидации, если есть.
        $_SESSION['errors'] = $errorMessage;
    } else {
        // Маршрут до страницы регистрации.
        $route = 'auth/registry';
        // Экранирую данные.
        $user = escapeData($user);
        // true, если введенные пользователем пароли совпадают.
        $isPasswordsMatch = isPasswordsMatch($user['password'], $user['confirm_password']);

        // Если введенные пользователем пароли не совпали.
        if ($isPasswordsMatch === false) {
            $_SESSION['errors'] = 'Пароли не совпадают' . "\n";
        } else {
            // true, если введенная пользователем почта уже существует, иначе false.
            $isUserEmailExists = isUserEmailExists($user['email']);

            // Если есть такая почта:
            if ($isUserEmailExists === true) {
                $_SESSION['errors'] = 'Пользователь с этими данными уже существует!' . "\n";
            } else {
                // Создаем пользователя и получаем его айди.
                $lastId = addUser($user);

                // Если при создании пользователя произошла ошибка.
                if ($lastId === 0) {
                    $_SESSION['errors'] = 'Произошла непредвиденная ошибка!' . "\n";
                } else {
                    // Добавляем данные пользователя в сессию.
                    $_SESSION['notification']['email'] = $user['email'];
                    $_SESSION['notification']['password'] = $user['password'];

                    // Маршрут до страницы с уведомлением об успешной регистрации.
                    $route = '/auth/notification';
                }
            }
        }
        header("Location: {$route}");
    }
}

// Путь до страницы с регистрацией.
$content = render($currentAction['view']);
