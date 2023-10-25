<?php

namespace Dino\Module\Http\Controllers;

use Dino\Base\Facades\BaseHelper;
use Dino\Base\Http\Controllers\BaseController;
use Dino\Module\Services\ModuleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class ModuleController extends BaseController
{
    public function __construct(protected ModuleService $moduleService)
    {
    }

    public function index()
    {
        if (File::exists(module_path('.DS_Store'))) {
            File::delete(module_path('.DS_Store'));
        }

        $modules = BaseHelper::scanFolder(module_path());

        $list = [];
        if (!empty($modules)) {
            $activeModules = get_active_modules();
            foreach ($modules as $module) {
                if (File::exists(module_path($module . '/.DS_Store'))) {
                    File::delete(module_path($module . '/.DS_Store'));
                }

                $modulePath = module_path($module);

                if (!File::isDirectory($modulePath) || !File::exists($modulePath . '/module.json')) {
                    continue;
                }

                $content = BaseHelper::getFileData($modulePath . '/module.json');
                if (!empty($content)) {
                    if (!in_array($module, $activeModules)) {
                        $content['status'] = 0;
                    } else {
                        $content['status'] = 1;
                    }

                    $content['path'] = $module;
                    $content['image'] = null;

                    $screenshot = 'vendor/core/modules/' . $module . '/screenshot.png';

                    if (File::exists(public_path($screenshot))) {
                        $content['image'] = asset($screenshot);
                    } elseif (File::exists($modulePath . '/screenshot.png')) {
                        $content['image'] = 'data:image/png;base64,' . base64_encode(File::get($modulePath . '/screenshot.png'));
                    } else {
                        $content['image'] = asset('cms/images/module-image.png');
                    }

                    $list[] = (object) $content;
                }
            }
        }

        if ($list) {
            $list = collect($list)->sortBy('name');
        }

        return view('core/module::index', compact('list'));
    }

    public function update(Request $request)
    {
        $module = strtolower($request->input('name'));

        if (!$this->moduleService->validateModule($module)) {
            return response()->json([
                'status' => false,
                'message' => trans('core/module::module.invalid_plugin'),
            ]);
        }

        try {
            $activatedModule = get_active_modules();

            if (!in_array($module, $activatedModule)) {
                $result = $this->moduleService->activate($module);
            } else {
                $result = $this->moduleService->deactivate($module);
            }

            if ($result['error']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => trans('core/module::module.update_module_status_success'),
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $module = strtolower($request->input('name'));

        if (!$this->moduleService->validateModule($module)) {
            return response()->json([
                'status' => false,
                'message' => trans('core/module::module.invalid_module'),
            ]);
        }

        try {
            $result = $this->moduleService->remove($module);

            if ($result['error']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => trans('core/module::module.remove_module_success'),
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
