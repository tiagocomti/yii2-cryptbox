<?php

namespace tiagocomti\cryptbox\api\responses;

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