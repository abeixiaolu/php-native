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
            <?php foreach ($actions as $action): ?>
                <?php extract($action, EXTR_OVERWRITE | EXTR_PREFIX_ALL, 'action'); ?>
                <tr>
                    <td><?= $action_id; ?></td>
                    <td>
                        <a href="/action/<?= $action_id; ?>">
                            <?= $action_name; ?>
                        </a>
                    </td>
                    <td><?= $action_description; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php ab_template_render_pager($url, $page, $total_page); ?>
</main>