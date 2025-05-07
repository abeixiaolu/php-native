<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="/default.css">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <?php extract($user, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'user'); ?>
                <tr>
                    <td><?= $user_id; ?></td>
                    <td>
                        <a href="/user/<?= $user_id; ?>">
                            <?= $user_username; ?>
                        </a>
                    </td>
                    <td><?= $user_first_name; ?></td>
                    <td><?= $user_last_name; ?></td>
                    <td><?= $user_email; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>