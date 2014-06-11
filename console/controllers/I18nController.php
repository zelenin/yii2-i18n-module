<?php

namespace Zelenin\yii\modules\I18n\console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use Zelenin\yii\modules\I18n\models\Message;
use Zelenin\yii\modules\I18n\models\SourceMessage;

class I18nController extends Controller
{
    public function actionImport($sourcePath)
    {
        $sourcePath = realpath(Yii::getAlias($sourcePath));
        if (!is_dir($sourcePath)) {
            throw new Exception("The source path $sourcePath is not a valid directory.");
        }
        $files = FileHelper::findFiles($sourcePath, ['only' => ['*.php']]);

        foreach ($files as $file) {
            $relativePath = trim(str_replace([$sourcePath, '.php'], '', $file), '/,\\');
            $relativePath = FileHelper::normalizePath($relativePath, '/');
            $relativePath = explode('/', $relativePath, 2);
            if (count($relativePath) > 1) {
                $language = $relativePath[0];
                $category = $relativePath[1];
                $messages = require $file;
                foreach ($messages as $sourceMessage => $_message) {
                    $params = [
                        'category' => $category,
                        'message' => $sourceMessage
                    ];

                    $sourceMessage = SourceMessage::find()->where($params)->one();
                    if (!$sourceMessage) {
                        $sourceMessage = new SourceMessage;
                        $sourceMessage->setAttributes($params, false);
                        $sourceMessage->save(false);
                    }
                    $message = Message::find()->where([
                        'id' => $sourceMessage->id,
                        'language' => $language
                    ])->one();
                    if ($message && $message->translation === null && !empty($_message)) {
                        $message->translation = $_message;
                        $message->save(false);
                    }
                    if (!$message && !empty($_message)) {
                        $message = new Message;
                        $message->setAttributes([
                            'id' => $sourceMessage->id,
                            'language' => $language,
                            'translation' => $_message
                        ], false);
                        $message->save(false);
                    }
                }
            }
        }
    }
}
