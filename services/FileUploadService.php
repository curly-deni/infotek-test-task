<?php

namespace app\services;

use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileUploadService
{
    public string $uploadDir;
    public string $baseUrl;

    public function __construct()
    {
        $this->uploadDir = env('UPLOAD_DIR', '@app/web/uploads');
        $this->baseUrl = env('UPLOAD_BASE_URL', '/uploads');
    }

    public function upload(string $inputName, bool $multiple = false): string|array|null
    {
        $dir = \Yii::getAlias($this->uploadDir);
        FileHelper::createDirectory($dir);

        if ($multiple) {
            $files = UploadedFile::getInstancesByName($inputName);
            if (!$files) return null;

            $urls = [];
            foreach ($files as $file) {
                $url = $this->saveFile($file, $dir);
                if ($url) $urls[] = $url;
            }

            return $urls ?: null;
        }

        $file = UploadedFile::getInstanceByName($inputName);
        return $file ? $this->saveFile($file, $dir) : null;
    }

    protected function saveFile(UploadedFile $file, string $dir): ?string
    {
        $fileName = uniqid() . '.' . $file->extension;
        $filePath = $dir . '/' . $fileName;

        if ($file->saveAs($filePath)) {
            return rtrim($this->baseUrl, '/') . '/' . $fileName;
        }

        return null;
    }
}
