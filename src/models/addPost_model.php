<?php

/** Функция валидации поста.
 * @param array $data
 * @return string|null
 */
function validatePost(array $data): ?string
{
    $result = null;

    // Валидация slug.
    if (empty($data['slug'])) {
        $result .= 'Заполните поле slug' . "\n";
    } elseif (\mb_strlen($data['slug']) > 255) {
        $result .= 'Поле slug не должно превышать 255 символов' . "\n";
    }

    // Валидация title.
    if (empty($data['title'])) {
        $result .= 'Заполните поле title' . "\n";
    } elseif (\mb_strlen($data['title']) > 255) {
        $result .= 'Поле title не должно превышать 255 символов' . "\n";
    }

    // Возвращаю строку ошибок, либо null
    return $result;
}

/** Функция валидация изображения поста.
 * @param array $data
 * @return string|null
 */
function validatePostImage(array $data): ?string
{
    $result = null;

    // Максимальный размер изображения.
    $maxFileSize = 1 * 1024 * 1024;
    // Разрешенные типы изображения.
    $allowedExtensions = ['jpeg', 'png', 'gif', 'webp', 'jpg'];

    // Получаю тип загруженного изображения.
    $extension = \pathinfo($data['image_post']['name'], PATHINFO_EXTENSION);

    // Валидация изображения поста.
    if (empty($data['image_post']['name'])) {
        $result .= 'Аватар обязателен' . "\n";
    } elseif (!\in_array($extension, $allowedExtensions)) {
        $result .= 'Недопустимый тип файла' . "\n";
    } elseif ($data['image_post']['size'] > $maxFileSize) {
        $result .= 'Размер файла превышает допустимый' . "\n";
    }

    // Возвращаю строку ошибок, либо null
    return $result;
}

/** Функция добавления поста.
 * @param array $data
 * @return int
 */
function addPost(array $data): int
{
    // Запрос на добавления данных нового поста.
    $query = 'INSERT INTO posts 
(category_id, user_id, slug, title, content, image, count_view, is_active, created_at, updated_at)
VALUES(:category_id, :user_id, :slug, :title, :content, :image, :count_view, :is_active, :created_at, :updated_at)';

    // Подготавливаю запрос на выполнение.
    $sth = connectionDB()->prepare($query);

    // Исполняю подготовленный запрос.
    $sth->execute([
        ':category_id' => $data['category_id'],
        ':user_id' => $data['user_id'],
        ':slug' => $data['slug'],
        ':title' => $data['title'],
        ':content' => $data['content'],
        ':image' => $data['image'],
        ':count_view' => '0',
        ':is_active' => $data['is_active'],
        ':created_at' => \date('Y-m-d H:i:s'),
        ':updated_at' => \date('Y-m-d H:i:s'),
    ]);

    // Получаю айди последнего созданного поста.
    return connectionDB()->lastInsertId();
}

/** Функция загрузки изображения поста.
 * @param array $dataImage
 * @return string
 */
function uploadImage(array $dataImage): string
{
    // Путь до папки с изображениями.
    $filePath = __DIR__ . '\..\..\uploads\\' . \uniqid() . $dataImage['image_post']['name'];

    // Перемещаю путь до файла.
    \move_uploaded_file($dataImage['image_post']['tmp_name'], $filePath);

    // Возвращаю путь до нужного изображения поста.
    return '\..\\' . \strstr($filePath, 'uploads');
}
