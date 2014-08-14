<?php

namespace Zelenin\yii\modules\I18n;

use yii\i18n\MissingTranslationEvent;
use Yii;
use Zelenin\yii\modules\I18n\models\SourceMessage;

class Module extends \yii\base\Module
{
    public $pageSize = 50;

    public static function module()
    {
        return static::getInstance();
    }

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('zelenin/modules/i18n', $message, $params, $language);
    }

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $params = [
            'category' => $event->category,
            'message' => $event->message
        ];
        $sourceMessage = SourceMessage::find()
            ->where($params)
            ->with('messages')
            ->one();

        if (!$sourceMessage) {
            $sourceMessage = new SourceMessage;
            $sourceMessage->setAttributes($params, false);
            $sourceMessage->save(false);
        }
        $sourceMessage->initMessages();
        $sourceMessage->saveMessages();
    }
}
