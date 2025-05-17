<?php
$can_read = ab_auth_is_authorized("ReadRole");
?>

<main>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
                <?php extract($role, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'role'); ?>
                <tr>
                    <td><?= $role_id; ?></td>
                    <td>
                        <?php if ($can_read): ?>
                            <a href="/role/<?= $role_id; ?>">
                                <?= $role_name; ?>
                            </a>
                        <?php else: ?>
                            <?= $role_name; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $role_description; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php ab_template_render_pager($url, $page, $total_page); ?>
</main>