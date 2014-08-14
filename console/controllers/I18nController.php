<?php

namespace Zelenin\yii\modules\I18n\console\controllers;

use yii\console\Controller;
use Yii;
use yii\console\Exception;
use yii\helpers\FileHelper;
use Zelenin\yii\modules\I18n\models\Message;
use Zelenin\yii\modules\I18n\models\SourceMessage;

class I18nController extends Controller
{
    /**
     * @param string $sourcePath
     * @throws Exception
     */
    public function actionImport($sourcePath)
    {
        $sourcePath = realpath(Yii::getAlias($sourcePath));
        if (!is_dir($sourcePath)) {
            throw new Exception('The source path ' . $sourcePath . ' is not a valid directory.');
        }
        $translationsFiles = FileHelper::findFiles($sourcePath, ['only' => ['*.php']]);

        foreach ($translationsFiles as $translationsFile) {
            $relativePath = trim(str_replace([$sourcePath, '.php'], '', $translationsFile), '/,\\');
            $relativePath = FileHelper::normalizePath($relativePath, '/');
            $relativePath = explode('/', $relativePath, 2);
            if (count($relativePath) > 1) {
                $language = $this->prompt('Enter language.', ['default' => $relativePath[0]]);
                $category = $this->prompt('Enter category.', ['default' => $relativePath[1]]);

                $translations = require_once $translationsFile;
                if (is_array($translations)) {
                    foreach ($translations as $sourceMessage => $translation) {
                        if (!empty($translation)) {
                            $sourceMessage = $this->getSourceMessage($category, $sourceMessage);
                            $this->setTranslation($sourceMessage, $language, $translation);
                        }
                    }
                }
            }
        }
        echo PHP_EOL . 'Done.' . PHP_EOL;
    }

    /**
     * @param string $category
     * @param string $message
     * @return SourceMessage
     */
    private function getSourceMessage($category, $message)
    {
        $params = [
            'category' => $category,
            'message' => $message
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
        return $sourceMessage;
    }

    /**
     * @param SourceMessage $sourceMessage
     * @param string $language
     * @param string $translation
     */
    private function setTranslation($sourceMessage, $language, $translation)
    {
        /** @var Message[] $messages */
        $messages = $sourceMessage->messages;
        if (isset($messages[$language]) && $messages[$language]->translation === null) {
            $messages[$language]->translation = $translation;
            $messages[$language]->save(false);
        } elseif (!isset($messages[$language])) {
            $message = new Message;
            $message->setAttributes([
                'language' => $language,
                'translation' => $translation
            ], false);
            $sourceMessage->link('messages', $message);
        }
    }

    public function actionFlush()
    {
        $tableNames = [
            Message::tableName(),
            SourceMessage::tableName()
        ];
        $db = Yii::$app->getDb();
        foreach ($tableNames as $tableName) {
            $db->createCommand()
                ->delete($tableName)
                ->execute();
        }
        echo PHP_EOL . 'Done.' . PHP_EOL;
    }
}
