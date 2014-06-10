<?php

namespace Zelenin\yii\modules\I18n;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $moduleId = Yii::$app->getModule('i18n')->id;
        $app->getUrlManager()->addRules([
            $moduleId . '/update/<id:\d+>' => $moduleId . '/default/update',
            $moduleId => $moduleId . '/default/index',
        ], false);
    }
}
