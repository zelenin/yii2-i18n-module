<?php

namespace Zelenin\yii\modules\I18n\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class SourceMessage extends ActiveRecord
{
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->sourceMessageTable;
    }

    public function rules()
    {
        return [
            ['message', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('zelenin/i18n', 'ID'),
            'category' => Yii::t('zelenin/i18n', 'Category'),
            'message' => Yii::t('zelenin/i18n', 'Message')
        ];
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
    }

    public static function getCategories()
    {
        return SourceMessage::find()->select('category')->distinct('category')->asArray()->all();
    }

    public function initMessages()
    {
        $messages = [];
        foreach (Yii::$app->getI18n()->languages as $language) {
            if (!isset($this->messages[$language])) {
                $message = new Message;
                $message->language = $language;
                $messages[$language] = $message;
            } else {
                $messages[$language] = $this->messages[$language];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    public function saveMessages()
    {
        /** @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
        }
    }
}
