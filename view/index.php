<?php

foreach ($posts as $post) {
    $userName = getUserByPost($post['user_id']);
    ?> <img src="<?= $post['image'] ?>" alt="Картинка поста"> <?php
    echo 'Ник ' . $userName['username'] . '<br>';
    echo 'Название поста '. $post['title'] . '<br>';
    echo 'Контент '. $post['content'] . '<br>';
    echo 'Просмотры ' . $post['count_view'] . '<br>';
    echo 'Дата создания ' .$post['created_at'] . '<br>';
    ?>
    <?php if (($_SESSION['user']['id'] == $post['user_id'])): ?>
        <form action="/remove_post" method="POST">
            <button type="submit" name="slug" value="<?= $post['slug'] ?>" class="btn btn-primary">Удалить</button>
        </form>
        <a href="blog/edit_post/?id_post=<?=$post['id_post']; ?>">Изменить</a>
    <?php endif ?>
    <a href="blog/show_post/?id_post=<?=$post['id_post']; ?>">Открыть</a>
    <?php echo '-----------------' . '<br>';
}

for ($i = 0; $i < $pageCount; $i++ ) {
    $html .= '<li><a href="?start=' . ($i * $limit)  . '">' . ($i + 1)  . '</a></li>';
}

// Выводим панель управления страницами.
if (!($pageCount <= 1)) {
    echo '<ul class="pagination">' . $html . '</ul>';
}