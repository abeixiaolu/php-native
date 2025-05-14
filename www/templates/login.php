<main class="login-form">
    <?php if (isset($auth_error)): ?>
        <div class="alert alert-danger"><?= $auth_error ?></div>
    <?php endif ?>
    <form method="post">
        <div <?= isset($errors['username']) ? 'class="has-error"' : '' ?>>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= $username ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error-msg"><?= $errors['username'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['password']) ? 'class="has-error"' : '' ?>>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?= $password ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="error-msg"><?= $errors['password'] ?></span>
            <?php endif ?>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</main>