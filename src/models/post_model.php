<?php

/* Получение поста по айди.
 *
 */
function getPost($id): array
{
    $query = 'SELECT * FROM posts WHERE id=?';

    $sth = connectionDB()->prepare($query);

    $sth->execute([$id]);

    return (array)$sth->fetch();
}

/* Изменение данных поста.
 *
 */
function editPost(array $data, string $id): int
{
    $query =
        'UPDATE posts 
        SET category_id = :category_id, 
            slug = :new_slug, 
            title = :title, 
            content = :content, 
            image = :image, 
            is_active = :is_active,
            updated_at = :updated_at
        WHERE id = :id LIMIT 1';

    $sth = connectionDB()->prepare($query);

    $sth->execute([
        ':id' => $id,
        ':category_id' => $data['category_id'],
        ':new_slug' => $data['slug'],
        ':title' => $data['title'],
        ':content' => $data['content'],
        ':image' => $data['image_path'],
        ':is_active' => $data['is_active'],
        ':updated_at' => \date('Y-m-d H:i:s'),
    ]);

    return (int)connectionDB()->lastInsertId();
}