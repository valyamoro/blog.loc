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
        $result .= 'Заполните поле почты' . "\n";
    } elseif (\strlen($data['email']) > 255) {
        $result .= 'Неверная почта' . "\n";
    }

    // Валидация пароля пользователя.
    if (empty($data['password'])) {
        $result .= 'Заполните поле пароль' . "\n";
    } elseif (\strlen($data['password']) > 255) {
        $result .= 'Неверный пароль' . "\n";
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
 * @return array
 */
function getUser(string $email): array
{
    // Получаем все данные пользователя.
    $query = 'SELECT * FROM users WHERE email=? LIMIT 1';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);
    // Запускаем подготовленный запрос на выполнение, передавая туда почту.
    $sth->execute([$email]);

    // Возвращаю массив данных пользователя.
    return $sth->fetch();
}
