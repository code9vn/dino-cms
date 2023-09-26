<?php

namespace Dino\Media\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;

class MediaController extends BaseController
{
    public function index()
    {
        return view('core/media::index');
    }
}
