<?php

namespace app\controllers;

use app\models\Post;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;

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
    public function actionCreate(): \yii\web\Response
    {
        $post = new Post();
        $post->load(Yii::$app->request->post(), '');
        if ($post->save()) {
            return $this->asJson(['status' => 'success']);
        }
        return $this->asJson(['status' => 'error', 'errors' => $post->getErrors()]);
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
/*        var_dump(Yii::$app->request->post('id'));
        die;*/
        $post = Post::findOne(Yii::$app->request->post('id'));
        if ($post->delete()) {
            return $this->asJson(['status' => 'success']);
        }
        return $this->asJson(['status' => 'error']);
    }

}
