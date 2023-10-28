<?php

/** Валидация данных пользователя из формы авторизации.
 * @param array $data
 * @return null|string
 */
function validateUser(array $data): ?string
{
    $result = null;

    // Валидация почты пользователя.
    if (empty($data['email'])) {
        $result .= 'Заполните поле почты' . PHP_EOL;
    } elseif (!\filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $result .= 'Некорректная почта!' . PHP_EOL;
    }

    // Валидация пароля пользователя.
    if (empty($data['password'])) {
        $result .= 'Заполните поле пароль' . PHP_EOL;
    } elseif (\is_numeric($data['password'])) {
        $result .= 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (!\preg_match('/[A-Z]/', $data['password'])) {
        $result .= 'Пароль должен содержать минимум одну заглавную букву' . PHP_EOL;
    } elseif (\mb_strlen($data['password'], 'utf8') <= 5) {
        $result .= 'Пароль содержит меньше 5 символов' . PHP_EOL;
    } elseif (\mb_strlen($data['password'], 'utf8') > 15) {
        $result .= 'Пароль больше 15 символов' . PHP_EOL;
    }

    // Возвращаю ошибки валидации.
    return $result;
}

/** Экранирование данных пользователя.
 * @param array $data
 * @return array
 */
function escapeData(array $data): array
{
    $escapeData = [];

    foreach ($data as $key => $value) {
        // Экранирую и преобразую приходящие данные с помощью специальных функций.
        $escapeData[$key] = \htmlspecialchars(\strip_tags(\trim($value)));
    }
    // Возвращают массив экранированных данных.
    return $escapeData;
}

/** Получаю данные пользователя по почте.
 * @param string $email
 * @return mixed
 */
function getUser(string $email)
{
    // Получаем все данные пользователя.
    $query = 'SELECT * FROM users WHERE email=? LIMIT 1';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);
    // Запускаем подготовленный запрос нвы полнение, передвая туда почту.
    $sth->execute([$email]);

    // Массив данных пользователя.
    return $sth->fetch();
}
