<nav class="pager">
    <ul>
        <li>
            <?php if ($page <= 1): ?>
                <span disabled>&larr;</span>
            <?php else: ?>
                <a href="/users?page=<?= $page - 1 ?>">&larr;</a>
            <?php endif; ?>
        </li>
        <?php for ($p = 1; $p <= $total_page; $p++): ?>
            <li>
                <?php if ($page == $p): ?>
                    <span class="current"><?= $p ?></span>
                <?php else: ?>
                    <a href="/users?page=<?= $p ?>"><?= $p ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
        <li>
            <?php if ($page >= $total_page): ?>
                <span disabled>&rarr;</span>
            <?php else: ?>
                <a href="/users?page=<?= $page + 1 ?>">&rarr;</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>