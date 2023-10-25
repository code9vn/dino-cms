<?php

namespace Dino\Base\Helpers;

use Exception;
use Illuminate\Support\Facades\File;

class BaseHelper
{
    public static function getAdminPrefix(): string
    {
        return config('core.base.common.admin_dir');
    }

    public function scanFolder(string $path, array $ignoreFiles = []): array
    {
        if (File::isDirectory($path)) {
            $data = array_diff(scandir($path), array_merge(['.', '..', '.DS_Store'], $ignoreFiles));
            natsort($data);

            return $data;
        }

        return [];
    }


    public function getFileData(string $file, bool $convertToArray = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($convertToArray) {
                return json_decode($file, true);
            }

            return $file;
        }

        if (!$convertToArray) {
            return null;
        }

        return [];
    }

    public function saveFileData(string $path, array|string|null $data, bool $json = true): bool
    {
        try {
            if ($json) {
                $data = $this->jsonEncodePrettify($data);
            }

            if (!File::isDirectory(File::dirname($path))) {
                File::makeDirectory(File::dirname($path), 493, true);
            }

            File::put($path, $data);

            return true;
        } catch (Exception $exception) {
            info($exception->getMessage());

            return false;
        }
    }

    public function jsonEncodePrettify(array|string|null $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
