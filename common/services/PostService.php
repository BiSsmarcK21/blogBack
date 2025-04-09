<?php

namespace common\services;

use app\models\Image;
use app\models\Post;
use Exception;
use Throwable;
use Yii;
use yii\web\UploadedFile;

class PostService
{
    public function getPostsList(): array
    {
        try {
            $posts = Post::find()->all();

            if (!$posts) {
                throw new Exception('Не существует ни единого поста.');
            }

            return [
                'status' => 'success',
                'message' => 'Список постов успешно найден.',
                'posts_list:' => $posts
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getOnePost(int $id): array
    {
        try {
            $post = Post::findOne($id);

            if (!$post) {
                throw new Exception("Поста с id: {$id} не существует.");
            }

            return [
                'status' => 'success',
                'message' => "Пост с id: {$id} успешно найден.",
                'post:' => $post
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

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
    public function updatePost(array $postData, array $files): array
    {
        $post = Post::findOne($postData['post_id']);

        if (!$post) {
            throw new Exception('Данный пост не найден.');
        }

        unset($postData['post_id']);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$post->load($postData, '')) {
                throw new Exception('Ошибка загрузки данных поста: ' . json_encode($post->errors));
            }

            if (!$post->validate() || !$post->save()) {
                throw new Exception('Ошибка при сохранении поста: ' . json_encode($post->errors));
            }

            $transaction->commit();

            return [
                'status' => 'success',
                'message' => 'Пост успешно обновлён.',
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

    public function deletePost(int $id): array
    {
        try {
            $post = Post::findOne($id);

            if (!$post) {
                throw new Exception("Поста с id: {$id} не существует.");
            }

            try {
                $post->delete();
            } catch (Throwable $e) {
                return [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }

            return [
                'status' => 'success',
                'message' => "Пост с id: {$id} успешно удалён.",
                'post_id' => $post->id,
            ];
        } catch (Exception $e) {
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