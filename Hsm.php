<?php

namespace tiagocomti\cryptbox;

use tiagocomti\cryptbox\models\api\AwsS3;
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
                $string = Yii::$app->cache->get("secret_key_server");
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