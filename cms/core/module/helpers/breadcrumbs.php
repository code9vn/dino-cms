<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('module.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard.index');
    $trail->push(trans('core/module::module.breadcrumb_title'), route('module.admin.index'));
});
