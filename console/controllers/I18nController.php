<?php

namespace Zelenin\yii\modules\I18n\console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use Zelenin\yii\modules\I18n\models\Message;
use Zelenin\yii\modules\I18n\models\SourceMessage;

class I18nController extends Controller
{
    /**
     * @param string $sourcePath
     * @throws Exception
     */
    public function actionImport($sourcePath = null)
    {
        if (!$sourcePath) {
            $sourcePath = $this->prompt('Enter a source path');
        }
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
     * @param string $messagePath
     * @param string $category
     * @throws Exception
     */
    public function actionExport($messagePath = null, $category = null)
    {
        if (!$messagePath) {
            $messagePath = $this->prompt('Enter a message path');
        }
        $messagePath = realpath(Yii::getAlias($messagePath));
        if (!is_dir($messagePath)) {
            throw new Exception('The message path ' . $messagePath . ' is not a valid directory.');
        }

        if (!$category) {
            $category = $this->prompt('Enter an exporting category');
        }
        if (empty($category)) {
            throw new Exception('The $category is empty.');
        }

        $sourceMessages = SourceMessage::find()
            ->where('category = :category', [':category' => $category])
            ->orderBy('message')
            ->all();

        $messages = [];

        foreach ($sourceMessages as $sourceMessage) {
            $translations = $sourceMessage->messages;
            foreach (Yii::$app->getI18n()->languages as $language) {
                $messages[$language][$sourceMessage['message']] = isset($translations[$language]) && !empty($translations[$language]['translation']) ? $translations[$language]['translation'] : '';
            }
        }

        foreach ($messages as $language => $translations) {
            $translationsFile = FileHelper::normalizePath($messagePath . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . str_replace('\\', '/', $category) . '.php');
            if (!is_file($translationsFile)) {
                $dir = dirname($translationsFile);
                if (!FileHelper::createDirectory($dir)) {
                    throw new Exception('Directory ' . $dir . ' is not created');
                }
            }
            ksort($translations);

            $array = VarDumper::export($translations);
            $content = <<<EOD
<?php

return $array;

EOD;

            file_put_contents($translationsFile, $content);
            echo PHP_EOL . 'Saved to ' . $translationsFile . PHP_EOL;
        }
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
