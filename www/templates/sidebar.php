<?php
function ab_is_active($path)
{
    return str_starts_with($_SERVER['REQUEST_URI'], $path) ? 'active' : '';
}
?>

<div class="sidebar">
    <ul>
        <li class="sidebar-title">Categories</li>
        <li class="<?= ab_is_active('/categor'); ?>">
            <a href="/categories">Categories</a>
            <a href="/category/add">+</a>
        </li>
        <li class="sidebar-title">Access Control</li>
        <li class="<?= ab_is_active('/user'); ?>">
            <a href="/users">Users</a>
            <a href="/user/add">+</a>
        </li>
        <li class="<?= ab_is_active('/role'); ?>">
            <a href="/roles">Roles</a>
            <a href="/role/add">+</a>
        </li>
        <li class="<?= ab_is_active('/action'); ?>">
            <a href="/actions">Actions</a>
            <a href="/action/add">+</a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a class="button" href="/logout">Logout</a>
    </div>
</div>