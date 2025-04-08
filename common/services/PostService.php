<?php

namespace common\services;

use app\models\Image;
use app\models\Post;
use Exception;
use Yii;
use yii\web\UploadedFile;

class PostService
{
    public function createPost(array $postData, array $files): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $post = new Post();

            if (!$post->load($postData, '')) {
                throw new Exception('Ошибка загрузки данных поста: ' . json_encode($post->errors));
            }

            if (!$post->validate() || !$post->save()) {
                throw new Exception('Ошибка при сохранении поста: ' . json_encode($post->errors));
            }

            if (!empty($files)) {
                foreach ($files as $file) {
                    $this->savePostImage($post->id, $file);
                }
            }

            $transaction->commit();

            return [
                'status' => 'success',
                'message' => 'Пост успешно добавлен.',
                'post_id' => $post->id
            ];
        } catch (Exception $e) {
            $transaction->rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @throws Exception
     */
    private function savePostImage(int $id, UploadedFile $file): void
    {
        $imageService = new ImageService();
        $fileName = $imageService->uploadImages($file);

        if (!$fileName) {
            throw new Exception('Ошибка при загрузке файла: ' . $file->name);
        }

        $image = new Image();
        $image->path = $fileName;

        if (!$image->validate() || !$image->save()) {
            throw new Exception('Ошибка при сохранни изображения: ' . json_encode($image->errors));
        }

        Yii::$app->db->createCommand()->insert('post_images', [
            'post_id' => $id,
            'image_id' => $image->id
        ])->execute();
    }
}