<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// USER
Breadcrumbs::for('user.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard.index');
    $trail->push(trans('core/user::user.breadcrumb_title'), route('user.admin.index'));
});
Breadcrumbs::for('user.admin.create', function (BreadcrumbTrail $trail) {
    $trail->parent('user.admin.index');
    $trail->push(trans('core/user::user.breadcrumbs.create'), route('user.admin.create'));
});
Breadcrumbs::for('user.admin.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('user.admin.index');
    $trail->push(trans('core/user::user.breadcrumbs.edit') . ' #' . $id, route('user.admin.edit', $id));
});

// ROLE
Breadcrumbs::for('role.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.admin.index');
    $trail->push(trans('core/user::role.breadcrumb_title'), route('role.admin.index'));
});
Breadcrumbs::for('role.admin.create', function (BreadcrumbTrail $trail) {
    $trail->parent('role.admin.index');
    $trail->push(trans('core/user::role.breadcrumbs.create'), route('role.admin.create'));
});
Breadcrumbs::for('role.admin.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('role.admin.index');
    $trail->push(trans('core/user::role.breadcrumbs.edit') . ' #' . $id, route('role.admin.edit', $id));
});
