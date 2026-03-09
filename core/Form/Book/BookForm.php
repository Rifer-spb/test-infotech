<?php

namespace Core\Form\Book;

use Core\Entity\Book\Book;
use Core\Helper\YearHelper;
use Yii;
use yii\base\Model;
use Core\Entity\Author\Author;
use yii\helpers\ArrayHelper;

class BookForm extends Model
{
    public $authors;
    public $title;
    public $description;
    public $year;
    public $isbn;
    public $filename;
    public $imageFile;

    public function __construct(private ?Book $book = null, $config = [])
    {
        if ($this->book !== null) {
            $this->authors = ArrayHelper::getColumn($this->book->authors, 'id');
            $this->title = $this->book->title;
            $this->description = $this->book->description;
            $this->year = $this->book->year;
            $this->isbn = $this->book->isbn;
            $this->filename = $this->book->filename;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['description', 'filename'], 'default', 'value' => null],
            [['title', 'year', 'isbn'], 'required'],
            [['description'], 'string'],
            [['title', 'filename'], 'string', 'max' => 255],

            [['year'], 'in', 'range' => array_keys(YearHelper::getList())],

            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'match', 'pattern' => '/^(?:\d[- ]?){9}[\dX]$|^(?:\d[- ]?){13}$/',
                'message' => 'Неверный формат ISBN'],
            [['isbn'], 'unique',
                'targetClass' => Book::class,
                'filter' => $this->book ? ['!=', 'id', $this->book->id] : null,
                'message' => 'Книга с таким ISBN уже существует'
            ],


            [['authors'], 'required'],
            [['authors'], 'each', 'rule' => [
                'exist',
                'targetClass' => Author::class,
                'targetAttribute' => 'id'
            ]],
            [['authors'], 'filter', 'filter' => 'array_filter'],

            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg, jpeg, webp, gif',
                'mimeTypes' => 'image/jpeg, image/png, image/webp, image/gif',
                'maxSize' => 5 * 1024 * 1024,
                'tooBig' => 'Файл слишком большой. Максимальный размер 5MB',
                'wrongExtension' => 'Разрешены только изображения: {extensions}',
                'wrongMimeType' => 'Разрешены только изображения',
                'maxFiles' => 1,
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'authors' => Yii::t('app', 'Автор'),
            'title' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'year' => Yii::t('app', 'Год выпуска'),
            'isbn' => Yii::t('app', 'ISBN')
        ];
    }
}
