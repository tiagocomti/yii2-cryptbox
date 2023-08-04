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
use yii\helpers\Console;

class HsmController extends Controller
{
    public function actionUploadMyKey($path){
        \Yii::$app->hsm->uploadMyKey($path);
    }

    public function actionUpdateMyKey(){
        \Yii::$app->hsm->updateMyKey();
    }

    public function actionStorageItem($itemName, $path){
        $file = fopen($path, 'rb');
        if(is_resource($file)) {
            $file = (stream_get_contents($file));
        }
        $upload = \Yii::$app->hsm->uploadItem($itemName, $file);
        BaseConsole::output($this->ansiFormat($upload, Console::FG_GREEN));
    }

    public function actionGetItem($itemName, $pathToSave){
        $key = \Yii::$app->hsm->getMyItem($itemName);
        $myfile = fopen($pathToSave, "w+");
        chown($pathToSave,"www");
        fwrite($myfile, $key);
        fclose($myfile);
    }

}