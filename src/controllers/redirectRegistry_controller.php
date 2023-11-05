<?php

if (empty($_SESSION['success'])) {
    \header('Location: /');
}

$metaTitle = 'Вы зарегистрировались!';

$content = render($currentAction['view']);
