<?php
function ab_is_active($path)
{
    return str_starts_with($_SERVER['REQUEST_URI'], $path) ? 'active' : '';
}
?>

<div class="sidebar">
    <ul>
        <li class="<?= ab_is_active('/user'); ?>">
            <a href="/users">Users</a>
            <a href="/user/add">+</a>
        </li>
        <li class="<?= ab_is_active('/role'); ?>">
            <a href="/roles">Roles</a>
            <a href="/role/add">+</a>
        </li>
    </ul>
</div>