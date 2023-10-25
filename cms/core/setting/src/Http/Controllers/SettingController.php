<?php

namespace Dino\Setting\Http\Controllers;

use Dino\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    public function index()
    {
        return view('core/setting::index');
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        foreach ($data as $config_name => $config_value) {
            setting()->set($config_name, $config_value);
        }
        setting()->save();

        cache()->flush();

        return redirect()->route('setting.admin.index')->with('flash_data', ['type' => 'success', 'message' => 'Lưu cấu hình thành công']);
    }
}
