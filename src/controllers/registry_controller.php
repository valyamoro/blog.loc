<?php

$metaTitle = 'Регистрация';

if ($_POST['registry'] === '1') {
    $user = $_POST;
    $errorMessage = validateUser($user);

    if (!\is_null($errorMessage)) {
        $_SESSION['errors'] = $errorMessage;
    } else {
        $route = '/auth/registry';

        $user = escapeData($user);
        $isPasswordsMatch = isPasswordsMatch($user['password'], $user['confirm_password']);

        if ($isPasswordsMatch === false) {
            $_SESSION['errors'] = 'Пароли не совпадают' . "\n";
        } else {
            $isUserEmailExists = isUserEmailExists($user['email']);

            if ($isUserEmailExists === true) {
                $_SESSION['errors'] = 'Пользователь с этими данными уже существует!' . "\n";
            } else {
                $user['password'] = \password_hash($user['password'], PASSWORD_DEFAULT);
                $lastId = addUser($user);

                if ($lastId === 0) {
                    $_SESSION['errors'] = 'Произошла непредвиденная ошибка!' . "\n";
                } else {
                    $_SESSION['success'] = [
                        'email' => $user['email'],
                        'password' => $user['password'],
                    ];
                    $route = '/auth/notification';
                }
            }
        }
        header("Location: {$route}");
        die;
    }
}

$content = render($currentAction['view']);
