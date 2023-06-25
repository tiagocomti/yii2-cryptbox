<?php

namespace tiagocomti\cryptbox\models\api;


use Aws\Command;
use tiagocomti\cryptbox\api\responses\S3Setup;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;

/**
 * Description of AwsS3
 *
 * @author Carlos Alberto FH
 */
class AwsS3 {

    // metodo upload

    /**
     * @throws \Exception
     */
    public static function uploadFileS3($bucket, $path, $key, $source, $options = []) {
        $file_name = $path . $key;
        $class = new self();
        $s3 = $class->createConnection();
        $uploader = new ObjectUploader(
            $s3,
            $bucket,
            $file_name,
            $source,
            'private',
            $options
        );
        try {
            $result = $uploader->upload();
            if ($result["@metadata"]["statusCode"] == '200') {
                return $result->get('ObjectURL');
            }
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param S3Setup|null $s3Setup
     * @return null
     */
    public static function getFileS3($id, S3Setup $s3Setup = null){
        $class = new self();
        try {
            $s3 = $class->createConnection($s3Setup);
            return $s3->getCommand('GetObject', [
                'Bucket' => $s3Setup->bucket,
                'Key' => $id,
                'SaveAs' => $s3Setup->key
            ]);
        } catch (S3Exception|\Exception $e) {
            \Yii::error($e->getMessage(),"api");
            \Yii::error($e->getMessage(),"command");
            return null;
        }
    }

    // metodo delete
    public static function deleteFileS3($bucket, $key) {
        try {
            $class = new self();
            $s3 = $class->createConnection();
            $result = $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $key
            ]);
            return true;
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getAuthenticatedIten(S3Setup $s3Setup,$id){
        $class = new self();
        try {
            $s3 = $class->createConnection($s3Setup);

            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => $s3Setup->bucket,
                'Key' => $id,
                'SaveAs' => $s3Setup->key
            ]);

            $request = $s3->createPresignedRequest($cmd, '+5 minutes');
            return file_get_contents((String)$request->getUri());

        } catch (S3Exception|\Exception $e) {
            \Yii::error($e->getMessage(),"api");
            \Yii::error($e->getMessage(),"command");
            return null;
        }
    }

    /**
     * @throws \Exception
     */
    private function createConnection(S3Setup $s3Setup = null): S3Client
    {
        if(!$s3Setup){
            $s3Setup = self::getCredentialsByFile(\Yii::$app->hsm->crendPath."/".\Yii::$app->hsm->crendJson);
        }
        return new S3Client([
            'version' =>$s3Setup->version,
            'region'  => $s3Setup->region,
            'credentials' => [
                'key'    => $s3Setup->key,
                'secret' => $s3Setup->secret
            ]
        ]);
    }

    public static function getCredentialsByFile($file): S3Setup{
        if(!file_exists($file)){
            throw new \Exception("O arquivo ". $file. "Não existe, crie como json e adicione o bytearray de senha nele (Lembre-se de nunca comitar ele)");
        }
        $content = file_get_contents($file);
        return new S3Setup(trim($content));
    }
}