<?php
extract($role);
?>

<main>
    <form method="post">
        <input type="hidden" name="id" value="<?= $role['id'] ?>">
        <div <?= isset($errors['name']) ? 'class="has-error"' : '' ?>>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="error-msg"><?= $errors['name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['description']) ? 'class="has-error"' : '' ?>>
            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?= $description ?>">
            <?php if (isset($errors['description'])): ?>
                <span class="error-msg"><?= $errors['description'] ?></span>
            <?php endif ?>
        </div>
        <div>
            <button type="submit">Submit</button>
            <a href="/users"><button type="button" class="secondary"> Cancel</button></a>
        </div>
    </form>
    <?php if ($role['id'] > 0): ?>
        <div class="user-roles-table">
            <h3>Add Action to Role</h3>
            <table class="">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($role_actions) > 0): ?>
                        <?php foreach ($role_actions as $action): ?>
                            <?php extract($action, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'action'); ?>
                            <tr>
                                <td><?= $action_id; ?></td>
                                <td><?= $action_name; ?></td>
                                <td><?= $action_description; ?></td>
                                <td>
                                    <form class="inline-form" style="margin-top: 0;" method="post">
                                        <input type="hidden" name="action" value="delete_action">
                                        <input type="hidden" name="id" value="<?= $role['id'] ?>">
                                        <input type="hidden" name="action_id" value="<?= $action_id ?>">
                                        <button link type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-data">No actions assigned to this role</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <form method="post" class="inline-form">
                <input type="hidden" name="action" value="add_action">
                <input type="hidden" name="id" value="<?= $role['id'] ?>">
                <div class="inline-form-item">
                    <label for="action">Action</label>
                    <select name="action_id" id="action" <?= count($other_actions) === 0 ? 'disabled' : '' ?>>
                        <?php if (count($other_actions) > 0): ?>
                            <?php foreach ($other_actions as $action): ?>
                                <option value="<?= $action['id'] ?>"><?= $action['name'] ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="0">No actions available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" <?= count($other_actions) === 0 ? 'disabled' : '' ?>>Add</button>
            </form>
        </div>
    <?php endif; ?>
</main>