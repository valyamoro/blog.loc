<?php

/** Валидация данных пользователя.
 * @param array $data
 * @return array
 */
function validateUserData(array $data): array
{
    // Создаем пустой массив, в будущем будет содержать сообщения об ошибках валидации.
    $msg = [];
    // Валидация почты.
    if (empty($data['email'])) {
        $msg[] = 'Заполните поле почты' . PHP_EOL;
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $msg[] = 'Некорректная почта!' . PHP_EOL;
    } elseif (\mb_strlen($data['email'], 'utf8') <= 14) {
        $msg[] = 'Почта должна содержать более 14 символов' . PHP_EOL;
    } elseif (!preg_match('/^[^!№;#$%^&*()]+$/u', $data['email'])) {
        $msg[] = 'Почта содержит недопустимые символы' . PHP_EOL;
    }
    // Валидация пароля.
    if (empty($data['password'])) {
        $msg[] = 'Заполните поле пароль' . PHP_EOL;
    } elseif (\is_numeric($data['password'])) {
        $msg[] = 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (!\preg_match('/[A-Z]/', $data['password'])) {
        $msg[] = 'Пароль должен содержать минимум одну заглавную букву' . PHP_EOL;
    } elseif (\mb_strlen($data['password'], 'utf8') <= 5) {
        $msg[] = 'Пароль содержит меньше 5 символов' . PHP_EOL;
    } elseif (\mb_strlen($data['password'], 'utf8') > 15) {
        $msg[] = 'Пароль содержит больше 15 символов' . PHP_EOL;
    }
    // Валидация имени пользователя.
    if (empty($data['user_name'])) {
        $msg[] = 'Заполните поле имя' . PHP_EOL;
    } elseif (\preg_match('#[^а-яa-z]#ui', $data['user_name'])) {
        $msg[] = 'Имя содержит недопустимые символы' . PHP_EOL;
    } elseif (\mb_strlen($data['user_name'], 'utf8') > 15) {
        $msg[] = 'Имя содержит больше 15 символов' . PHP_EOL;
    } elseif (\mb_strlen($data['user_name'], 'utf8') <= 3) {
        $msg[] = 'Имя содержит менее 4 символов' . PHP_EOL;
    }
    // Возвращаю массив сообщений с ошибками валидации.
    return $msg;
}

/** Экранирование данных.
 * @param array $data
 * @return array
 */
function escapeData(array $data): array
{
    // Создаю пустой массив, в будущем содержащий экранированные данные.
    $quoteData = [];

    // Перебираю приходящие данные.
    foreach ($data as $key => $value) {
        // Преобразую спец. символы, удаляю HTML, PHP-теги, пробелы из элемента массива и присваиваю его новому массиву.
        $quoteData[$key] = htmlspecialchars(strip_tags(trim($value)));
    }
    // Возвращают массив экранированных данных.
    return $quoteData;
}

/** Проверяем существует ли приходящая почта от пользователя в базе даных.
 * @param string $email
 * @return bool
 */
function checkUserEmail(string $email): bool
{
    // Формируем запрос на получение почты.
    $query = 'SELECT email FROM users WHERE email=? LIMIT 1';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);

    // Запускаем подготовленный запрос на выполнение.
    $sth->execute([$email]);
    // Возвращаем true, если введенная пользователем почта совпала с существующей в базе данных.
    return (bool) $sth->fetch();
}

/** Добавляем нового пользователя в базу данных.
 * @param array $data
 * @return int
 */
function addUser(array $data): int
{
    // Формируем запрос на вставку данных нового пользователя.
    $query = 'INSERT INTO users (role_id, username, email, password, hash, created_at, updated_at)
    VALUES(:role_id, :username, :email, :password, :hash, :created_at, :updated_at)';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);

    // Запускаем подготовленный запрос на выполнения, передавая туда массив значений для именованных параметров.
    $sth->execute([
        ':role_id' => '0',
        ':username' => $data['user_name'],
        ':email' => $data['email'],
        ':password' => $data['password'],
        ':hash' => '0',
        ':created_at' => date('Y-m-d H:i:s'),
        ':updated_at' => date('Y-m-d H:i:s'),
    ]);

    // Возвращаем айди последней созданной записи.
    return (int) connectionDB()->lastInsertId();
}

/** Проверяем совпадают ли введенные пользователем пароли.
 * @param string $password
 * @param string $confirmPassword
 * @return bool
 */
function confirmPassword(string $password, string $confirmPassword): bool
{
    // Если пароли совпали, то возвращаем true, иначе false.
    return $password === $confirmPassword;
}
