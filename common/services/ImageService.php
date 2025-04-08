<?php

namespace common\services;

use app\models\Post;
use Exception;
use Yii;
use yii\web\UploadedFile;

class ImageService
{
    /**
     * @throws Exception
     */
    public function uploadImages(UploadedFile $file): string
    {
        $tempDir = Yii::getAlias('@runtime/temp/images');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        if (!is_writable($tempDir)) {
            throw new Exception("Дирректория {$tempDir} недоступна для записи.");
        }

        $tempPath = $tempDir . '/' . uniqid() . '.' . $file->extension;

        if (str_contains($file->tempName, '.tmp')) {
            if (!$file->saveAs($tempPath)) {
                $error = error_get_last();
                throw new Exception('Ошибка при сохраннении изображения: ' . ($error['message'] ?? 'неизвестаня ошибка'));
            }
        } else {
            if (!copy($file->tempName, $tempPath)) {
                $error = error_get_last();
                throw new Exception('Ошибка при копировании файла: ' . ($error['message'] ?? 'неизвестная ошибка'));
            }
        }

        return basename($tempPath);
    }
}