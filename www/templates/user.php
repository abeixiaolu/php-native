<?php
extract($user);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="/default.css">
</head>

<body>
    <form method="post">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div <?= isset($errors['username']) ? 'class="has-error"' : '' ?>"'>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= $username ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error-msg"><?= $errors['username'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['first_name']) ? 'class="has-error"' : '' ?>"'>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= $first_name ?>">
            <?php if (isset($errors['first_name'])): ?>
                <span class="error-msg"><?= $errors['first_name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['last_name']) ? 'class="has-error"' : '' ?>"'>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= $last_name ?>">
            <?php if (isset($errors['last_name'])): ?>
                <span class="error-msg"><?= $errors['last_name'] ?></span>
            <?php endif ?>
        </div>
        <div <?= isset($errors['email']) ? 'class="has-error"' : '' ?>"'>
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
</body>

</html>