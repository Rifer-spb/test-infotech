<?php

namespace Core\Entity;

use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class AbstractEntity extends \yii\db\ActiveRecord
{
    /**
     * @throws Exception
     */
    public function saveOrFail($runValidation = true, $attributeNames = null): bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new \RuntimeException("Не удалось сохранить модель " . static::class);
        }
        return true;
    }

    /**
     * @return bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function deleteOrFail(): bool
    {
        if (!parent::delete()) {
            throw new \RuntimeException("Не удалось удалить модель " . static::class);
        }
        return true;
    }

    public static function findById($id): ?static
    {
        return static::findOne($id);
    }

    public static function getById($id): static
    {
        if (!$model = static::findById($id)) {
            self::notFountException();
        }

        return $model;
    }

    public static function findBy(array $where): ?static
    {
        return static::findOne($where);
    }

    public static function getBy(array $where): static
    {
        if (!$model = static::findBy($where)) {
            self::notFountException();
        }
        return $model;
    }

    public static function hasById($id): bool
    {
        return static::find()->where(["id" => $id])->exists();
    }

    public static function findByIdOrHttpNotFound($id): ?static
    {
        if (!$model = static::findById($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

    public static function getAllForSelect(string $from = 'id', string $to = 'name'): array
    {
        return ArrayHelper::map(static::find()->all(), $from, $to);
    }

    private static function notFountException(): void
    {
        throw new NotFoundException('Объект класса ' . static::class . ' не найден.');
    }
}
