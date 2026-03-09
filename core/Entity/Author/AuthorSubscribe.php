<?php

namespace Core\Entity\Author;

use Core\Entity\AbstractEntity;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%authors_subscribe}}".
 *
 * @property int $author_id
 * @property string $phone
 *
 * @property Author $author
 */
class AuthorSubscribe extends AbstractEntity
{
    public static function tableName(): string
    {
        return '{{%authors_subscribe}}';
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 255],
            [['author_id', 'phone'], 'unique', 'targetAttribute' => ['author_id', 'phone']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => Yii::t('app', 'Автор'),
            'phone' => Yii::t('app', 'Телефон'),
        ];
    }

    public static function create(Author $author, string $phone): self
    {
        $model = new self();
        $model->author_id = $author->id;
        $model->phone = $phone;
        $model->saveOrFail();
        return $model;
    }

    public static function hasByAuthorAndPhone(int $authorId, string $phone): bool
    {
        return self::find()
            ->where(['phone' => $phone])
            ->andWhere(['author_id' => $authorId])
            ->exists();
    }

    /**
     * @param array $authorIds
     * @return AuthorSubscribe[]
     */
    public static function getAllByAuthorIds(array $authorIds): array
    {
        return self::find()->where(['author_id' => $authorIds])->all();
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
