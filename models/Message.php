<?php

namespace Zelenin\yii\modules\I18n\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class Message extends ActiveRecord
{
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageTable;
    }

    public function rules()
    {
        return [
            ['language', 'required'],
            ['language', 'string', 'max' => 16],
            ['translation', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('zelenin/i18n', 'ID'),
            'language' => Yii::t('zelenin/i18n', 'Language'),
            'translation' => Yii::t('zelenin/i18n', 'Translation')
        ];
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
