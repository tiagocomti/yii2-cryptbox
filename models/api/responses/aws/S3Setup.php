<?php

namespace tiagocomti\cryptbox\models\api\responses\aws;
use tiagocomti\cryptbox\models\api\responses\BaseResponses;
class S3Setup extends BaseResponses
{
    public $secret;
    public $region;
    public $bucket;
    public $version;
    public $key;

    public function __construct($values, $namespace = __NAMESPACE__)
    {
        parent::__construct($values, $namespace);
    }
}