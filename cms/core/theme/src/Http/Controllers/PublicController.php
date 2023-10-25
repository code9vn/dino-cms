<?php

namespace Dino\Theme\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;
use Dino\Theme\Facades\Theme;

class PublicController extends BaseController
{
    public function getIndex()
    {
        return Theme::scope('index')->render();
    }

    public function getView()
    {
        abort(404);
    }
}
