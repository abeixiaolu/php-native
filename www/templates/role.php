<?php
extract($role);

$can_edit = (ab_auth_is_authorized("UpdateRole") && $role['id'] > 0) || (ab_auth_is_authorized("CreateRole") && $role['id'] === 0);
?>

<main>
    <h3>Role Details</h3>
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
            <?php if ($can_edit): ?>
                <button type="submit">Submit</button>
            <?php endif; ?>
            <a href="/roles"><button type="button" class="secondary"> Cancel</button></a>
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
                                        <?php if ($can_edit): ?>
                                            <button link type="submit">Remove</button>
                                        <?php endif; ?>
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
                <?php if ($can_edit): ?>
                    <button type="submit" <?= count($other_actions) === 0 ? 'disabled' : '' ?>>Add</button>
                <?php endif; ?>
            </form>
        </div>
    <?php endif; ?>
</main>