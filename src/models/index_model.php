<?php

/** Вывод постов с валидацией
 * @param int $start
 * @param int $limit
 * @return array
 */
function getAllPosts(int $start, int $limit): array
{
    // Запрос на получение определенного количества постов.
    $query = "SELECT * FROM posts LIMIT $start, $limit";

    // Подготавливаем и выполняем запрос.
    $sth = connectionDB()->prepare($query);

    // Выполняем запрос.
    $sth->execute();

    // Получаем нужное кол-во постов с нужной позиции.
    return $sth->fetchAll();
}

/** Получаю количество существующих постов.
 * @return int
 */
function getCountPosts(): int
{
    // Запрос на получение количества постов.
    $query = 'SELECT ' .
        ' COUNT(*) ' .
        'FROM ' .
        ' posts ';

    // Подготавливаем запрос без заполнителей.
    $sth = connectionDB()->query($query);
    // Возвращаем количество постов.
    return $sth->fetchColumn();
}

/**
 * @param $id
 * @return mixed
 */
function getUserByPost($id)
{
    // Получаю пользователя по айди.
    $query = 'SELECT username FROM users WHERE id = ? LIMIT 1';

    // Подготавливаем запрос на выполнение.
    $sth = connectionDB()->prepare($query);

    // Выполняем запрос, передавая туда айди поста.
    $sth->execute([$id]);

    // Возвращаем данные пользователя.
    return $sth->fetch();
}
