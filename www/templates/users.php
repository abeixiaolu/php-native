<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
  <link rel="stylesheet" href="default.css">
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
      <?php while($user = $statement->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><?= htmlspecialchars($user['id']); ?></td>
          <td><?= htmlspecialchars($user['username']); ?></td>
          <td><?= htmlspecialchars($user['first_name']); ?></td>
          <td><?= htmlspecialchars($user['last_name']); ?></td>
          <td><?= htmlspecialchars($user['email']); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>