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
          <th>Action</th>
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
            <td align="right">
              <form class="hidden" method="POST" action="/category/<?= $category['id'] ?>">
                <input type="hidden" name="id" value="<?= $category['id'] ?>" />
                <button link type="submit" <?= $category['depth'] == 0 ? "disabled" : "" ?>>
                  <i class="icon si-plus-square"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>