<?php

namespace Zelenin\yii\modules\I18n\components;

use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;

class I18N extends \yii\i18n\I18N
{
    public $sourceMessageTable = '{{%source_message}}';
    public $messageTable = '{{%message}}';
    public $languages;
    public $missingTranslationHandler = ['Zelenin\yii\modules\I18n\Module', 'missingTranslation'];

    public function init()
    {
        if (!$this->languages) {
            throw new InvalidConfigException('You should configure i18n component');
        }

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        parent::init();
    }
}
