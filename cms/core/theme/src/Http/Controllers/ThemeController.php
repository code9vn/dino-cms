<?php

namespace Dino\Theme\Http\Controllers;

use Dino\Base\Facades\BaseHelper;
use Dino\Base\Http\Controllers\BaseController;
use Dino\Theme\Services\ThemeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ThemeController extends BaseController
{
    public function __construct(protected ThemeService $themeService)
    {
    }

    public function index()
    {
        if (File::exists(module_path('.DS_Store'))) {
            File::delete(module_path('.DS_Store'));
        }

        return view('core/theme::index');
    }

    public function update(Request $request)
    {
        try {
            $result = $this->themeService->activate($request->input('theme'));

            if ($result['error']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => trans('core/theme::theme.active_succes'),
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
        $theme = strtolower($request->input('theme'));

        if (in_array($theme, BaseHelper::scanFolder(theme_path()))) {
            try {
                $result = $this->themeService->remove($theme);

                if ($result['error']) {
                    return response()->json([
                        'status' => false,
                        'message' => $result['message'],
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => trans('core/theme::theme.remove_theme_success'),
                ]);
            } catch (Exception $exception) {
                return response()->json([
                    'status' => false,
                    'message' => $exception->getMessage(),
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => trans('core/theme::theme.theme_is_not_existed'),
        ]);
    }
}
