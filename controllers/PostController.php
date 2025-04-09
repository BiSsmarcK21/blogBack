<?php

namespace app\controllers;

use app\models\Post;
use common\services\PostService;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

class PostController extends \yii\web\Controller
{
    public function actionGetPostsList(): \yii\web\Response
    {
        $service = new PostService();

        return $this->asJson($service->getPostsList());
    }

    /**
     * @throws Exception
     */
    public function actionGet(): \yii\web\Response
    {
        $id = Yii::$app->request->get('id');
        if (!$id) {
            throw new Exception('Нужно передать id поста.');
        }

        $service = new PostService();

        return $this->asJson($service->getOnePost($id));
    }

    public function actionCreate(): \yii\web\Response
    {
        $postData = Yii::$app->request->post();
        $files = UploadedFile::getInstancesByName('files');

        $service = new PostService();

        return $this->asJson($service->createPost($postData, $files));
    }

    /**
     * @throws \Exception
     */
    public function actionUpdate(): \yii\web\Response
    {
        $postData = Yii::$app->request->post();
        $files = UploadedFile::getInstancesByName('files');

        $service = new PostService();

        return $this->asJson($service->updatePost($postData, $files));
    }

    /**
     * @throws Exception
     */
    public function actionDelete(): \yii\web\Response
    {
        $id = Yii::$app->request->get('id');
        if (!$id) {
            throw new Exception('Необходимо передать id.');
        }

        $service = new PostService();

        return $this->asJson($service->deletePost($id));
    }
}
