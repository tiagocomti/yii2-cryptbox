<?php

namespace tiagocomti\cryptbox\commands;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'tiagocomti\cryptbox\commands\controllers';

    public function init()
    {
        parent::init();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'tiagocomti\cryptbox\commands\controllers';
        }
    }
}