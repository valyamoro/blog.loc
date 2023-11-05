<?php
// На рефакторинг потрачено 19 минут. Теперь функция getUser, возвращает либо массив, либо false.
$metaTitle = 'Авторизация';

if ($_POST['login'] === '1') {
    $credentials = $_POST;
    $errorMessage = validateUser($credentials);

    $route = '/auth/login';

    if (!\is_null($errorMessage)) {
        $_SESSION['errors'] = $errorMessage;
    } else {
        $credentials = escapeData($credentials);
        $user = getUser($credentials['email']);

        if ($user === false) {
            $_SESSION['errors'] = 'Вы ввели неверные данные.' . "\n";
        } else {
            if (!\password_verify($credentials['password'], $user['password'])) {
                $_SESSION['errors'] = 'Вы ввели неверные данные.' . "\n";
            } else {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['username'],
                    'email' => $user['email'],
                ];

                $route = '/auth/profile';
            }
        }
    }
    \header("Location: {$route}");
    die;
}

$content = render($currentAction['view']);
