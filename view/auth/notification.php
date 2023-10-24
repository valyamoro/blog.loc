<?php
if (empty($_SESSION['notification'])) {
    header('Location: /');
}

echo 'Вы зарегистрировались!';
echo "Ваша почта {$_SESSION['notification']['email']}<br>";
echo "Ваш пароль {$_SESSION['notification']['password']}<br>";
echo '<a href="/auth/profile">Ваш личный кабинет</a>';

