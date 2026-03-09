<?php

namespace Core\Entity\Author;

use Core\Entity\AbstractEntity;
use Core\Entity\Book\Book;
use Core\Entity\Book\BookAuthor;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "{{%authors}}".
 *
 * @property int $id
 * @property string $name
 *
 * @property Book[] $books
 * @property BookAuthor[] $bookAuthors
 */
class Author extends AbstractEntity
{
    public static function tableName(): string
    {
        return '{{%authors}}';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ФИО'),
        ];
    }

    /**
     * @param string $name
     * @return self
     * @throws Exception
     */
    public static function create(string $name): self
    {
        $model = new self();
        $model->name = $name;
        $model->saveOrFail();
        return $model;
    }

    public function hasBooks(): bool
    {
        return $this->getBooks()->exists();
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable(BookAuthor::tableName(), ['author_id' => 'id']);
    }
}
