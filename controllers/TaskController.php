<?php

namespace app\controllers;

use app\models\Task;
use Yii;

use yii\web\Controller;
use yii\web\Response;

class TaskController extends Controller
{
    public $modelClass = 'app\models\Task';

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
          [
              'title' => 'Task 1',
              'description' => 'Task 1 description',
              ],
                [
                'title' => 'Task 2',
                'description' => 'Task 2 description',
            ],
            [
                'title' => 'Task 3',
                'description' => 'Task 3 description',
            ],
        ];
    }

    public function actionGetTasks()
    {
        return 2;
    }
}