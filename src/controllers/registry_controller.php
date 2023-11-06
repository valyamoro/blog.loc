<?php
// Рефакторинг без добавления аватара - 10 минут.
//

$metaTitle = 'Регистрация';

if ($_POST['registry'] === '1') {
    $user = $_POST;
    $errorMessage = validateUser($user);

    if (!\is_null($errorMessage)) {
        $_SESSION['errors'] = $errorMessage;
    } else {
        $route = '/auth/registry';

        $user = escapeData($user);

        if ($user['password'] !== $user['confirm_password']) {
            $_SESSION['errors'] = 'Пароли не совпадают' . "\n";
        } else {
            $isUserEmailExists = isUserEmailExists($user['email']);

            if ($isUserEmailExists) {
                $_SESSION['errors'] = 'Пользователь с этими данными уже существует!' . "\n";
            } else {
                $password = $user['password'];
                $user['password'] = \password_hash($user['password'], PASSWORD_DEFAULT);

                $now = \date('Y-m-d H:i:s');
                $user['created_at'] = $now;
                $user['updated_at'] = $now;

                if (addUser($user) === 0) {
                    $_SESSION['errors'] = 'Произошла ошибка регистрации!' . "\n";
                } else {
                    $_SESSION['success'] = [
                        'email' => $user['email'],
                        'password' => $password,
                    ];

                    $route = '/auth/redirect_registry';
                }
            }
        }

        \header("Location: {$route}");
        die;
    }
}

$content = render($currentAction['view']);
