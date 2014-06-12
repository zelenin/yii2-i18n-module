<?php

namespace Zelenin\yii\modules\I18n;

use yii\base\BootstrapInterface;
use Yii;
use Zelenin\yii\modules\I18n\console\controllers\I18nController;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $moduleId = Yii::$app->getModule('i18n')->id;
            $app->getUrlManager()->addRules([
                'translations/<id:\d+>' => $moduleId . '/default/update',
                'translations' => $moduleId . '/default/index',
            ], false);
        }
        if ($app instanceof \yii\console\Application) {
            if (!isset($app->controllerMap['i18n'])) {
                $app->controllerMap['i18n'] = I18nController::className();
            }
        }
    }
}
