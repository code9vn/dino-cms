<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard.index', function (BreadcrumbTrail $trail) {
    $trail->push('<i class="ph-house me-1"></i>' . trans('core/dashboard::dashboard.breadcrumb_title'), route('dashboard.index'));
});
