<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%authors_subscribe}}`.
 */
class m260309_124927_create_authors_subscribe_table extends Migration
{
    private $tableName = '{{%authors_subscribe}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'author_id' => $this->integer()->notNull()->unsigned(),
            'phone' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-authors_subscribe', $this->tableName, ['author_id', 'phone']);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
