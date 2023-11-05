<?php

/** Валидация данных пользователя из формы авторизации.
 */
function validateUser(array $data): ?string
{
    $result = null;

    if (empty($data['email'])) {
        $result .= 'Заполните поле почты' . "\n";
    } elseif (\mb_strlen($data['email'], 'utf8') > 255) {
        $result .= 'Неверная почта' . "\n";
    }

    if (empty($data['password'])) {
        $result .= 'Заполните поле пароль' . "\n";
    } elseif (\mb_strlen($data['password'], 'utf8') > 255) {
        $result .= 'Неверный пароль' . "\n";
    }

    return $result;
}

/** Экранирование данных пользователя.
 */
function escapeData(array $data): array
{
    $escapeData = [];

    foreach ($data as $key => $value) {
        $escapeData[$key] = \htmlspecialchars(\strip_tags(\trim($value)));
    }

    return $escapeData;
}

/** Получаю данные пользователя по почте.
 */
function getUser(string $email): bool|array
{
    $query = 'SELECT * FROM users WHERE email=? LIMIT 1';

    $sth = connectionDB()->prepare($query);
    $sth->execute([$email]);

    return $sth->fetch();
}
