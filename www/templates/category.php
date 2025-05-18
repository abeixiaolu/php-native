<?php
extract($category);

$can_edit = true;
?>

<main>
    <h3>Category Details</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div <?= isset($errors['name']) ? 'class="has-error"' : '' ?>"'>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="error-msg"><?= $errors['name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['parent_id']) ? 'class="has-error"' : '' ?>"'>
            <label for="parent_id">Parent</label>
            <?php if ($categories > 0): ?>
                <select id="parent_id" name="parent_id">
                    <option value="0">Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $parent_id ? 'selected' : '' ?>>
                            <?= $category['depth'] > 0 ? str_repeat("&emsp;", $category['depth'] - 1) . "\u{2514}" : "" ?>
                            <?= $category['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['parent_id'])): ?>
                    <span class="error-msg"><?= $errors['parent_id'] ?></span>
                <?php endif ?>
            <?php else: ?>
                <select name="parent_id" disabled>
                    <option>No categories found</option>
                </select>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($can_edit): ?>
                <button type="submit">Submit</button>
            <?php endif; ?>
            <a href="/categories"><button type="button" class="secondary">Cancel</button></a>
        </div>
    </form>
</main>