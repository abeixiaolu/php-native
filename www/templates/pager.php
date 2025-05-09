<nav class="pager">
    <ul>
        <li>
            <?php if ($page <= 1): ?>
                <span disabled>&laquo;</span>
            <?php else: ?>
                <a href="<?= "$url?page=" . $page - 1 ?>">&laquo;</a>
            <?php endif; ?>
        </li>
        <?php for ($p = 1; $p <= $total_page; $p++): ?>
            <li>
                <?php if ($page == $p): ?>
                    <span class="current"><?= $p ?></span>
                <?php else: ?>
                    <a href="<?= "$url?page=$p" ?>"><?= $p ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
        <li>
            <?php if ($page >= $total_page): ?>
                <span disabled>&raquo;</span>
            <?php else: ?>
                <a href="<?= "$url?page=" . $page + 1 ?>">&raquo;</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>