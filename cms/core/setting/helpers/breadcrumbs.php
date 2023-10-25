<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('setting.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard.index');
    $trail->push(trans('core/setting::setting.breadcrumb_title'), route('setting.admin.index'));
});
