<?php

// Указываем заголовок документа.
$metaTitle = 'Регистрация';

// Если пользователь нажал на кнопку "Зарегистрироваться", то выполняем скрипт внутри условия.
if ($_POST['registry'] == 1) {
    // Записываем данные потенциального пользователя.
    $userData = $_POST;
    // Записываем сообщения об ошибках валидации, если они есть.
    $msg = validateUserData($userData);
    if (!empty($msg)) {
        // Объединяем все элементы массива ошибок валидации в строку.
        $msg = implode(' ', $msg);
        // Записываем в сессию строку с ошибками валидации.
        $_SESSION['msg'] = $msg;
    } else {
        // Экранируем данные потенциального пользователя.
        $userData = escapeData($userData);
        // Записываем true, если пароли совпадают.
        $matchPasswords = confirmPassword($userData['password'], $userData['confirm_password']);
        if (!$matchPasswords) {
            // Если пароли не совпали, записываем в сессию сообщение об ошибке.
            $_SESSION['msg'] = 'Пароли не совпадают';
        } else {
            // Если введенная пользователем почта уже есть в БД, то записываем true.
            $emailExist = checkUserEmail($userData['email']);
            // Если такая почта уже есть в БД, то выводим сообщение, иначе добавляем нового пользователя в БД.
            $emailExist ? $_SESSION['msg'] = 'Пользователь с этими данными уже существует!' : addUser($userData);
            // Выполняем блок кода, если введенной пользователем почты не существует в БД.
            if (!$emailExist) {
                // Записываем пароль и почту пользователя в сессию.
                $_SESSION['notification']['email'] = $userData['email'];
                $_SESSION['notification']['password'] = $userData['password'];
                // Перенаправляем пользователя на страницу с уведомлением об успешной регистрации.
                header('Location: /auth/notification');
            }
        }
    }
}

// Переменная содержащая путь до страницы с регистрацией.
$content = render($currentAction['view']);
