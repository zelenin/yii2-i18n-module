<?php

namespace Zelenin\yii\modules\I18n;

use yii\base\BootstrapInterface;
use Yii;
use yii\web\Application;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $moduleId = Yii::$app->getModule('i18n')->id;
            $app->getUrlManager()->addRules([
                'translations/<id:\d+>' => $moduleId . '/default/update',
                'translations' => $moduleId . '/default/index',
            ], false);
        }
    }
}
