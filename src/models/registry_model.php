<?php

/** Валидация данных пользователя.
 * @param array $data
 * @return null|string
 */
function validateUser(array $data): ?string
{
    $result = null;

    // Валидация почты.
    if (empty($data['email'])) {
        $result .= 'Заполните поле почты' . "\n";
    } elseif (!\filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $result .= 'Некорректная почта!' . "\n";
    } elseif (\mb_strlen($data['email'], 'utf8') <= 14) {
        $result .= 'Почта должна содержать более 14 символов' . "\n";
    } elseif (!\preg_match('/^[^!№;#$%^&*()]+$/u', $data['email'])) {
        $result .= 'Почта содержит недопустимые символы' . "\n";
    }

    // Валидация пароля.
    if (empty($data['password'])) {
        $result .= 'Заполните поле пароль' . "\n";
    } elseif (\is_numeric($data['password'])) {
        $result .= 'Пароль не должен содержать только цифры' . "\n";
    } elseif (!\preg_match('/[A-Z]/', $data['password'])) {
        $result .= 'Пароль должен содержать минимум одну заглавную букву' . "\n";
    } elseif (\mb_strlen($data['password'], 'utf8') <= 5) {
        $result .= 'Пароль содержит меньше 5 символов' . "\n";
    } elseif (\mb_strlen($data['password'], 'utf8') > 15) {
        $result .= 'Пароль содержит больше 15 символов' . "\n";
    }

    // Валидация имени пользователя.
    if (empty($data['user_name'])) {
        $result .= 'Заполните поле имя' . "\n";
    } elseif (\preg_match('#[^а-яa-z]#ui', $data['user_name'])) {
        $result .= 'Имя содержит недопустимые символы' . "\n";
    } elseif (\mb_strlen($data['user_name'], 'utf8') > 15) {
        $result .= 'Имя содержит больше 15 символов' . "\n";
    } elseif (\mb_strlen($data['user_name'], 'utf8') <= 3) {
        $result .= 'Имя содержит менее 4 символов' . "\n";
    }

    // Возвращаю строку с ошибками валидации.
    return $result;
}

/** Экранирование данных.
 * @param array $data
 * @return array
 */
function escapeData(array $data): array
{
    $result = [];

    foreach ($data as $key => $value) {
        // Экранирую и преобразую приходящие данные с помощью специальных функций.
        $result[$key] = \htmlspecialchars(\strip_tags(\trim($value)));
    }
    // Возвращают массив экранированных данных.
    return $result;
}

/** Проверяем существует ли приходящая почта от пользователя.
 * @param string $email
 * @return bool
 */
function isUserEmailExists(string $email): bool
{
    // Получаем почту.
    $query = 'SELECT email FROM users WHERE email=? LIMIT 1';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);
    // Запускаем подготовленный запрос на выполнение.
    $sth->execute([$email]);
    // true, если введенная пользователем почта совпала с существующей.
    return (bool) $sth->rowCount();
}

/** Добавляем нового пользователя в базу данных.
 * @param array $data
 * @return int
 */
function addUser(array $data): int
{
    // Добавляем нового пользователя.
    $query = 'INSERT INTO users (role_id, username, email, password, hash, created_at, updated_at)
    VALUES(:role_id, :username, :email, :password, :hash, :created_at, :updated_at)';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);

    // Сегодняшняя дата и время.
    $now = \date('Y-m-d H:i:s');
    // Запускаем подготовленный запрос на выполнения, передавая туда массив значений для именованных параметров.
    $sth->execute([
        ':role_id' => '0',
        ':username' => $data['user_name'],
        ':email' => $data['email'],
        ':password' => $data['password'],
        ':hash' => \password_hash($data['password'], PASSWORD_DEFAULT),
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);

    // Возвращаем айди последней созданной записи.
    return connectionDB()->lastInsertId();
}

/** Проверяем идентичны ли введенные пользователем пароли.
 * @param string $password
 * @param string $confirmPassword
 * @return bool
 */
function isPasswordsMatch(string $password, string $confirmPassword): bool
{
    // true, если пароли совпали.
    return $password === $confirmPassword;
}
