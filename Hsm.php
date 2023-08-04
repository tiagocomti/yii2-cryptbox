<?php

namespace tiagocomti\cryptbox;

use tiagocomti\cryptbox\models\api\AwsS3;
use tiagocomti\cryptbox\models\api\responses\aws\S3Setup;
use yii\db\Exception;
use yii;

class Hsm {
    const PROVIDER_AWS_S3 = '0';
    public $crendPath;
    public $provider;
    public $crendJson;

    private $secretName = "secret.key";
    public function __construct(){
        if(YII_ENV_DEV){
            $this->secretName = "secret-dev.key";
        }
    }

    public function uploadMyKey($path){
        if($this->provider === self::PROVIDER_AWS_S3){
            $s3 = new AwsS3();

            $file = fopen($path, 'rb');
            if(is_resource($file)) {
                $file = (stream_get_contents($file));
            }
            $photo = AwsS3::uploadFileS3('agf-sec','hsm/keys/',$this->secretName,Cryptbox::easyEncrypt($file,Cryptbox::getOurSecret()));
            return true;
        }
    }

    public function uploadItem($item_name, $content){
        if($this->provider === self::PROVIDER_AWS_S3){
            $s3 = new AwsS3();

            $resposta = AwsS3::uploadFileS3('agf-sec','hsm/keys/',$item_name,Cryptbox::easyEncrypt($content,Cryptbox::getOurSecret()));

            return $resposta;
        }
    }

    public function getMyItem($item_name){
        if(Yii::$app->cryptbox->enableCache === true) {
            if (Yii::$app->cache->get($item_name) === false) {
                $crend = AwsS3::getCredentialsByFile($this->crendPath.'/'.$this->crendJson);
                $key = AwsS3::getAuthenticatedIten($crend, "hsm/keys/" . $item_name);
                if(empty($key)){
                    throw new \Exception("Falha ao localizar chave ". $item_name." no bucket");
                }
                $key = Cryptbox::easyDecrypt($key, Cryptbox::getOurSecret());
                Yii::$app->cache->set($item_name, $key, (Yii::$app->cryptbox->timeCache) ?: 600000);
            }else{
                $key = Yii::$app->cache->get($item_name);
            }
        }else {
            $crend = AwsS3::getCredentialsByFile($this->crendPath . '/' . $this->crendJson);
            $key = AwsS3::getAuthenticatedIten($crend, "hsm/keys/" .$item_name);
            $key = Cryptbox::easyDecrypt($key, Cryptbox::getOurSecret());
        }

        return $key;
    }

    public function updateMyKey(){
        if(Yii::$app->cryptbox->enableCache === true) {
            if (Yii::$app->cache->get("secret_key_server") === false) {
                $crend = AwsS3::getCredentialsByFile($this->crendPath.'/'.$this->crendJson);
                $key = AwsS3::getAuthenticatedIten($crend, "hsm/keys/" . $this->secretName);
                if(empty($key)){
                    throw new \Exception("Falha ao localizar chave ". $this->secretName." no bucket");
                }
                $key = Cryptbox::easyDecrypt($key, Cryptbox::getOurSecret());
                Yii::$app->cache->set("secret_key_server", $key, (Yii::$app->cryptbox->timeCache) ?: 600000);
            }else{
                $key = Yii::$app->cache->get("secret_key_server");
            }
        }else {
            $crend = AwsS3::getCredentialsByFile($this->crendPath . '/' . $this->crendJson);
            $key = AwsS3::getAuthenticatedIten($crend, "hsm/keys/" . $this->secretName);
            $key = Cryptbox::easyDecrypt($key, Cryptbox::getOurSecret());
        }

        $myfile = fopen(Yii::$app->cryptbox->keysPath."server.key.enc", "w+");
        fwrite($myfile, $key);
        fclose($myfile);
        return $key;
    }
}