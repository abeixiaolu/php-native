<?php
extract($action);

$can_edit = (ab_auth_is_authorized("UpdateAction") && $action['id'] > 0) || (ab_auth_is_authorized("CreateAction") && $action['id'] === 0);
?>

<main>
    <form method="post">
        <input type="hidden" name="id" value="<?= $action['id'] ?>">
        <div <?= isset($errors['name']) ? 'class="has-error"' : '' ?>"'>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="error-msg"><?= $errors['name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['description']) ? 'class="has-error"' : '' ?>"'>
            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?= $description ?>">
            <?php if (isset($errors['description'])): ?>
                <span class="error-msg"><?= $errors['description'] ?></span>
            <?php endif ?>
        </div>
        <div>
            <?php if ($can_edit): ?>
                <button type="submit">Submit</button>
            <?php endif; ?>
            <a href="/actions"><button type="button" class="secondary">Cancel</button></a>
        </div>
    </form>
</main>