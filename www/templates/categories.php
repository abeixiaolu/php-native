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
          <th class="table-action">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $category): ?>
          <tr>
            <td><?= $category['id']; ?></td>
            <td>
              <?php if ($can_read): ?>
                <a href="/category/<?= $category['id']; ?>">
                  <?= $category['depth'] > 0 ? str_repeat("&emsp;", $category['depth'] - 1) . "\u{2514}" : "" ?>
                  <?= $category['name']; ?>
                </a>
              <?php else: ?>
                <?= $category['name']; ?>
              <?php endif; ?>
            </td>
            <td class="table-action">
              <form style="display: inline-block;" class="hidden" method="POST" action="/category/<?= $category['id'] ?>">
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="id" value="<?= $category['id'] ?>" />
                <?php $can_delete = $category['rgt'] == $category['lft'] + 1; ?>
                <button link type="submit" <?= $can_delete ? "" : "disabled" ?>>
                  <i class="icon si-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>