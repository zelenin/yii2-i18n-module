<?php

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\db\Schema;

class m140609_093837_addI18nTables extends Migration
{
    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function up()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category' => 'varchar(32) null',
            'message' => 'text null'
        ]);

        $this->createTable($messageTable, [
            'id' => Schema::TYPE_INTEGER . ' not null default 0',
            'language' => 'varchar(16) not null default ""',
            'translation' => 'text null'
        ]);
        $this->addPrimaryKey('id', $messageTable, ['id', 'language']);
        $this->addForeignKey('fk_source_message_message', $messageTable, 'id', $sourceMessageTable, 'id', 'cascade');
    }

    public function down()
    {
        echo 'm140609_093837_addI18nTables cannot be reverted.' . PHP_EOL;
        return false;
    }
}
