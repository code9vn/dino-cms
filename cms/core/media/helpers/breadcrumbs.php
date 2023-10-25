<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('media.admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard.index');
    $trail->push(trans('core/media::media.breadcrumb_title'), route('media.admin.index'));
});
