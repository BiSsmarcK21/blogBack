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

    public function actionIndex(): \yii\web\Response
    {
        $posts = Post::find()->all();
        return $this->asJson($posts);
    }

    /**
     * @throws Exception
     */
    public function actionCreate1(): \yii\web\Response
    {
        $post = new Post();
        $post->load(Yii::$app->request->post(), '');
        if ($post->save()) {
            return $this->asJson(['status' => 'success']);
        }
        return $this->asJson(['status' => 'error', 'errors' => $post->getErrors()]);
    }

    public function actionCreate(): \yii\web\Response
    {
        $postData =Yii::$app->request->post();
        $files = UploadedFile::getInstancesByName('files');

        $service = new PostService();

        return $this->asJson($service->createPost($postData, $files));
    }

    /**
     * @throws Exception
     */
    public function actionUpdate(): \yii\web\Response
    {
        $post = Post::findOne(Yii::$app->request->post('id'));
        $post->load(Yii::$app->request->post(), '');
        if ($post->validate() && $post->save()) {
            return $this->asJson(['status' => 'success']);
        }
        return $this->asJson(['status' => 'error', 'errors' => $post->errors]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(): \yii\web\Response
    {
        $post = Post::findOne(Yii::$app->request->post('id'));
        if ($post->delete()) {
            return $this->asJson(['status' => 'success']);
        }
        return $this->asJson(['status' => 'error']);
    }

}
