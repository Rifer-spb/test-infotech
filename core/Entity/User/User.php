<?php

namespace Core\Entity\User;

use Core\Entity\AbstractEntity;
use Core\Entity\NotFoundException;
use Core\Entity\User\Event\UserCreatedEvent;
use Core\Event\DomainEvent;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $password write-only password
 */
class User extends AbstractEntity implements \yii\web\IdentityInterface
{
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @param string $username
     * @param string $password
     * @return static
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function create(string $username, string $password): static
    {
        if (empty($username) || empty($password)) {
            throw new \DomainException('Необходимо указать логин и пароль.');
        }

        $user = new static();
        $user->username = $username;
        $user->email = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->saveOrFail();

        DomainEvent::add(new UserCreatedEvent($user));

        return $user;
    }

    public static function findIdentity($id): static
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username): ?static
    {
        return static::findOne(['username' => $username]);
    }

    public static function getByUsername($username): ?static
    {
        if (!$model = self::findByUsername($username)) {
            throw new NotFoundException('Пользователь не найден.');
        }
        return $model;
    }

    public static function findByPasswordResetToken($token): ?static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token]);
    }

    public static function findByVerificationToken($token): static
    {
        return static::findOne(['verification_token' => $token]);
    }

    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
