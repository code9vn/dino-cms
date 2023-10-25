<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('theme.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard.index');
    $trail->push(trans('core/theme::theme.breadcrumb_title'), route('theme.admin.index'));
});
