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
                        <a href="/role/<?= $role_id; ?>">
                            <?= $role_name; ?>
                        </a>
                    </td>
                    <td><?= $role_description; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php ab_template_render_pager($url, $page, $total_page); ?>
</main>