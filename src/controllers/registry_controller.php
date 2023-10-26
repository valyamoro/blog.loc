<?php

// Заголовок страницы.
$metaTitle = 'Регистрация';

// Пользователь нажал на кнопку регистрации:
if ($_POST['registry'] == 1) {
    // Присваиваю введенные пользователем данные.
    $user = $_POST;
    // Собираю ошибки валидации.
    $errorMessage = validateUser($user);

    if (!empty($errorMessage)) {
        // Помещаем в сесию все ошибки валидации, если есть.
        $_SESSION['errors'] = $errorMessage;
    } else {
        // Маршрут до страницы регистрации.
        $route = 'auth/registry';

        // Экранирую данные.
        $user = escapeData($user);
        // true, если пароли совпадают.
        $isTheyMatch = confirmPassword($user['password'], $user['confirm_password']);

        if (!$isTheyMatch) {
            // Если введенные пользователем пароли не совпали.
            $_SESSION['msg'] = 'Пароли не совпадают';
        } else {
            // true, если введенная пользователем почта уже существует.
            $isEmailExist = checkUserEmail($user['email']);

            // Если нет такой почты:
            if ($isEmailExist) {
                $_SESSION['msg'] = 'Пользователь с этими данными уже существует!';
            } else {
                // Создаем пользователя.
                addUser($user);
                // Добавляем данные пользователя в сессию.
                $_SESSION['notification']['email'] = $user['email'];
                $_SESSION['notification']['password'] = $user['password'];

                // Маршрут до страницы с уведомлением об успешной регистрации.
                $route = '/auth/notification';
            }
        }
        header("Location: {$route}");
    }
}

// Переменная содержащая путь до страницы с регистрацией.
$content = render($currentAction['view']);
