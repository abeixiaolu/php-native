<?php
extract($user);
?>

<main>
    <h3>User Details</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div <?= isset($errors['username']) ? 'class="has-error"' : '' ?>>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= $username ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error-msg"><?= $errors['username'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['first_name']) ? 'class="has-error"' : '' ?>>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= $first_name ?>">
            <?php if (isset($errors['first_name'])): ?>
                <span class="error-msg"><?= $errors['first_name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['last_name']) ? 'class="has-error"' : '' ?>>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= $last_name ?>">
            <?php if (isset($errors['last_name'])): ?>
                <span class="error-msg"><?= $errors['last_name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['email']) ? 'class="has-error"' : '' ?>>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?= $email ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error-msg"><?= $errors['email'] ?></span>
            <?php endif ?>
        </div>
        <div>
            <button type="submit">Submit</button>
            <a href="/users"><button type="button" class="secondary"> Cancel</button></a>
        </div>
    </form>
    <?php if ($user['id'] > 0): ?>
        <div class="user-roles-table">
            <h3>Add Role to User</h3>
            <table class="">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($user_roles) > 0): ?>
                        <?php foreach ($user_roles as $role): ?>
                            <?php extract($role, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'role'); ?>
                            <tr>
                                <td><?= $role_id; ?></td>
                                <td><?= $role_name; ?></td>
                                <td><?= $role_description; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty-data">No roles assigned to this user</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <form method="post" class="inline-form">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <div class="inline-form-item">
                    <label for="role">Role</label>
                    <select name="role_id" id="role" <?= count($other_roles) === 0 ? 'disabled' : '' ?>>
                        <?php if (count($other_roles) > 0): ?>
                            <?php foreach ($other_roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="0">No roles available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" <?= count($other_roles) === 0 ? 'disabled' : '' ?>>Add</button>
            </form>
        </div>
    <?php endif; ?>
</main>