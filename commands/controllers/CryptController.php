<?php

namespace tiagocomti\cryptbox\commands\controllers;

use app\models\Users;
use dektrium\user\models\User;
use tiagocomti\cryptbox\Cryptbox;
use yii\console\Controller;
use tiagocomti\cryptbox\helpers\Strings;
use yii\console\ExitCode;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\BaseConsole;

class CryptController extends Controller
{
    public function actionByPubKey($string, $hex){
        BaseConsole::output(Cryptbox::encryptByPublicK($string, $hex));
    }

    public function actionEncode($string){
        $string = Strings::convertTo32Bit($string);
        $byte_array = unpack('C*', $string);
        BaseConsole::output(json_encode($byte_array));
    }

    public function actionDecode($byteArray){
        BaseConsole::output(Strings::byteArrayToString(json_decode($byteArray, true)));
    }

    public function actionDbPassword(){
        $string = (BaseConsole::input($this->ansiFormat("password: ",BaseConsole::FG_GREEN)));
        $pass = Cryptbox::easyEncrypt($string, Cryptbox::getOurSecret());
        $byte_array = Strings::stringToByteArray($pass);
        BaseConsole::output($this->ansiFormat("Paste it in your db conf:", BaseConsole::FG_GREEN));
        BaseConsole::output(json_encode($byte_array));
    }

    public function actionCheckDb(){
        BaseConsole::output("start at: ". date('H:i:s'));
        try {
            \Yii::$app->db->open();
        }catch (Exception $e){
            echo $e->getMessage();
            return ExitCode::UNSPECIFIED_ERROR;
        }
        ActiveRecord::getDb();
        BaseConsole::output("base ok.");
        BaseConsole::output("end at: ". date('H:i:s'));
    }
}