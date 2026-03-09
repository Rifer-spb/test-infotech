<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books_authors}}`.
 */
class m260309_174141_create_books_authors_table extends Migration
{
    private $tableName = '{{%books_authors}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'author_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addPrimaryKey('pk-books_authors', $this->tableName, ['author_id', 'book_id']);

        $this->addForeignKey(
            'fk-author_id',
            $this->tableName,
            'author_id',
            '{{%authors}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-book_id',
            $this->tableName,
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createIndex('idx-books_authors-author_id', $this->tableName, 'author_id');
        $this->createIndex('idx-books_authors-book_id', $this->tableName, 'book_id');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
