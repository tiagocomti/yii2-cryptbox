<?php

namespace tiagocomti\cryptbox\commands\controllers;

use app\models\Users;
use dektrium\user\models\User;
use tiagocomti\cryptbox\Cryptbox;
use tiagocomti\cryptbox\Hsm;
use yii\console\Controller;
use tiagocomti\cryptbox\helpers\Strings;
use yii\console\ExitCode;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\BaseConsole;
class HsmController extends Controller
{
    public function actionUploadMyKey($path){
        \Yii::$app->hsm->uploadMyKey($path);
    }

    public function actionUpdateMyKey(){
        \Yii::$app->hsm->updateMyKey();
    }
}