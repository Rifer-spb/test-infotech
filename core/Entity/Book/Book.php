<?php

namespace Core\Entity\Book;

use Yii;
use yii\db\ActiveQuery;
use Core\Entity\AbstractEntity;
use Core\Entity\Author\Author;
use Core\Entity\Book\Event\CreatedBookEvent;
use Core\Event\DomainEvent;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property int $id Идентификатор
 * @property string $title Название
 * @property string|null $description Описание
 * @property string $year Год выпуска
 * @property string $isbn Международный идентификатор книги
 * @property string|null $filename Название файла фото главной страницы книги
 *
 * @property Author[] $authors
 * @property BookAuthor[] $bookAuthors
 */
class Book extends AbstractEntity
{
    public static function tableName(): string
    {
        return '{{%books}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'year' => Yii::t('app', 'Год выпуска'),
            'isbn' => Yii::t('app', 'ISBN')
        ];
    }

    /**
     * @param array $authorIds
     * @param string $title
     * @param string $description
     * @param int $year
     * @param string $isbn
     * @return static
     * @throws Exception
     */
    public static function create(array $authorIds, string $title, string $description, int $year, string $isbn): static
    {
        if (empty($authorIds) || empty($title) || empty($description) || empty($year) || empty($isbn)) {
            throw new \DomainException('Не все поля указаны.');
        }

        $model = new static();
        $model->title = $title;
        $model->description = $description;
        $model->year = $year;
        $model->isbn = $isbn;
        $model->saveOrFail();

        $model->loadAuthors($authorIds);

        DomainEvent::add(new CreatedBookEvent($model));

        return $model;
    }

    /**
     * @param array $authorIds
     * @param string $title
     * @param string $description
     * @param int $year
     * @param string $isbn
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function edit(array $authorIds, string $title, string $description, int $year, string $isbn): void
    {
        if (empty($authorIds) || empty($title) || empty($description) || empty($year) || empty($isbn)) {
            throw new \DomainException('Не все поля указаны.');
        }

        $this->title = $title;
        $this->description = $description;
        $this->year = $year;
        $this->isbn = $isbn;

        foreach ($this->bookAuthors as $bookAuthor) {
            $bookAuthor->deleteOrFail();
        }

        $this->loadAuthors($authorIds);
    }

    public function getPublicPhotoPath(): string
    {
        $uploadPath = Yii::getAlias('@web/uploads/books');
        return "{$uploadPath}/{$this->filename}";
    }

    public function uploadPhoto(UploadedFile $uploadedFile): void
    {
        $uploadPath = Yii::getAlias('@webroot/uploads/books');
        $newFilename = "book_{$this->id}_" . time() . ".{$uploadedFile->extension}";
        $newFilePath = "{$uploadPath}/{$newFilename}";

        if (!$uploadedFile->saveAs($newFilePath)) {
            throw new \DomainException("Не удалось сохранить загружаемый файл.");
        }

        $isNeedDeleteOldFile =
            !empty($this->filename)
            && file_exists($oldPath = "{$uploadPath}/{$this->filename}");

        if ($isNeedDeleteOldFile) {
            unlink($oldPath);
        }

        $this->filename = $newFilename;
    }

    public function getAuthorIds(): array
    {
        return ArrayHelper::getColumn($this->bookAuthors, 'author_id');
    }

    private function loadAuthors(array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            $author = Author::getById($authorId);
            BookAuthor::create($author, $this);
        }
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable(BookAuthor::tableName(), ['book_id' => 'id']);
    }
}
