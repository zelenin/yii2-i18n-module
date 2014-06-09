<?php

namespace Zelenin\yii\modules\I18n\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;

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
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation')
        ];
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
