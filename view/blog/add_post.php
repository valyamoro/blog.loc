<?php if (empty($_SESSION['user']['id']))  {
    header('Location: /');
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="category" class="form-label">Категория</label>
        <label for="category_id"></label><input type="text" name="category_id" class="form-control" id="category_id">
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">slug</label>
        <input type="text" name="slug" class="form-control" id="slug">
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" id="title">
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <input type="text" name="content" class="form-control" id="content">
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Фото поста</label>
        <input type="file" name="image_post" class="form-control" id="image_post">
    </div>
<!--Тут будет чекбокс-->
    <div class="mb-3">
        <label for="is_active" class="form-label">is_active</label>
        <input type="text" name="is_active" class="form-control" id="is_active">
    </div>

    <button type="submit" class="btn btn-primary">Добавить пост</button>
</form>
<?php if(!empty($_SESSION['errors'])): ?>
    <?php echo '<p class="msg"> ' . nl2br($_SESSION['errors']) . ' </p>'; ?>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>