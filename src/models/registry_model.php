<?php

/** Валидация данных пользователя.
 */
function validateUser(array $data): ?string
{
    $result = null;

    if (empty($data['email'])) {
        $result .= 'Заполните поле почты' . "\n";
    } elseif (!\filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $result .= 'Некорректная почта!' . "\n";
    } elseif (\mb_strlen($data['email'], 'utf8') <= 14) {
        $result .= 'Почта должна содержать более 14 символов' . "\n";
    } elseif (!\preg_match('/^[^!№;#$%^&*()]+$/u', $data['email'])) {
        $result .= 'Почта содержит недопустимые символы' . "\n";
    }

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

    if (empty($data['user_name'])) {
        $result .= 'Заполните поле имя' . "\n";
    } elseif (\preg_match('#[^а-яa-z]#ui', $data['user_name'])) {
        $result .= 'Имя содержит недопустимые символы' . "\n";
    } elseif (\mb_strlen($data['user_name'], 'utf8') > 15) {
        $result .= 'Имя содержит больше 15 символов' . "\n";
    } elseif (\mb_strlen($data['user_name'], 'utf8') <= 3) {
        $result .= 'Имя содержит менее 4 символов' . "\n";
    }

    return $result;
}

/** Экранирование данных.
 */
function escapeData(array $data): array
{
    $result = [];

    foreach ($data as $key => $value) {
        $result[$key] = \htmlspecialchars(\strip_tags(\trim($value)));
    }

    return $result;
}

/** Проверяем существует ли приходящая почта от пользователя.
 */
function isUserEmailExists(string $email): bool
{
    $query = 'SELECT email FROM users WHERE email=? LIMIT 1';

    $sth = connectionDB()->prepare($query);
    $sth->execute([$email]);

    return (bool)$sth->rowCount();
}

/** Добавляем нового пользователя в базу данных.
 */
function addUser(array $data): int
{
    $query = 'INSERT INTO users (role_id, username, email, password, hash, created_at, updated_at)
    VALUES(:role_id, :username, :email, :password, :hash, :created_at, :updated_at)';

    $sth = connectionDB()->prepare($query);

    $now = \date('Y-m-d H:i:s');

    $sth->execute([
        ':role_id' => '0',
        ':username' => $data['user_name'],
        ':email' => $data['email'],
        ':password' => $data['password'],
        ':hash' => '0',
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);

    return (int)connectionDB()->lastInsertId();
}

/** Проверяем идентичны ли введенные пользователем пароли.
 */
function isPasswordsMatch(string $password, string $confirmPassword): bool
{
    return $password === $confirmPassword;
}
