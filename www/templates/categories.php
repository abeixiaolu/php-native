<?php
$can_read = true;
?>

<main>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $category): ?>
          <?php extract($category, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'category'); ?>
          <tr>
            <td><?= $category_id; ?></td>
            <td>
              <?php if ($can_read): ?>
                <a href="/category/<?= $category_id; ?>">
                  <?= $category_depth > 0 ? str_repeat("&emsp;", $category_depth - 1) . "\u{2514}" : "" ?>
                  <?= $category_name; ?>
                </a>
              <?php else: ?>
                <?= $category_name; ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>