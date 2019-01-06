<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $activation_token
 * @property string $email
 * @property string $auth_key
 * @property integer $type
 * @property integer $status
 * @property integer $role
 * @property string $created_time
 * @property string $updated_time
 * @property string $first_name
 * @property string $last_name
 * @property integer $gender
 * @property integer $date_of_birth
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const TYPE_FRONTEND = 0;
    const TYPE_BACKEND = 1;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const ROLE_GUESS = 0;
    const ROLE_DEVELOPER = 1;
    const ROLE_ADMINISTRATOR = 2;
    const ROLE_MODERATOR = 3;
    const ROLE_WRITER = 4;

    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    /**
     * @return string[]
     */
    public function getTypeLabels()
    {
        return [
            self::TYPE_FRONTEND => 'Frontend',
            self::TYPE_BACKEND => 'Backend',
        ];
    }

    /**
     * @return string[]
     */
    public function getStatusLabels()
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DELETED => 'Deleted',
        ];
    }

    /**
     * @return string[]
     */
    public function getRoleLabels()
    {
        return [
            self::ROLE_GUESS => 'Guess',
            self::ROLE_DEVELOPER => 'Developer',
            self::ROLE_ADMINISTRATOR => 'Administrator',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_WRITER => 'Writer',
        ];
    }

    /**
     * @return string[]
     */
    public function getGenderLabels()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    /**
     * @return string
     */
    public function typeLabel()
    {
        $typeLabels = $this->getTypeLabels();
        if (isset($typeLabels[$this->type])) {
            return $typeLabels[$this->type];
        } else {
            return "$this->type";
        }
    }

    /**
     * @return string
     */
    public function statusLabel()
    {
        $statusLabels = $this->getStatusLabels();
        if (isset($statusLabels[$this->status])) {
            return $statusLabels[$this->status];
        } else {
            return "$this->status";
        }
    }

    /**
     * @return string
     */
    public function roleLabel()
    {
        $roleLabels = $this->getRoleLabels();
        if (isset($roleLabels[$this->role])) {
            return $roleLabels[$this->role];
        } else {
            return "$this->role";
        }
    }

    /**
     * @return string
     */
    public function genderLabel()
    {
        $genderLabels = $this->getGenderLabels();
        if (isset($genderLabels[$this->gender])) {
            return $genderLabels[$this->gender];
        } else {
            return "$this->gender";
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['type', 'default', 'value' => self::TYPE_BACKEND],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'default', 'value' => self::ROLE_DEVELOPER],
            ['type', 'in', 'range' => [
                self::TYPE_FRONTEND,
                self::TYPE_BACKEND,
            ]],
            ['status', 'in', 'range' => [
                self::STATUS_INACTIVE,
                self::STATUS_ACTIVE,
                self::STATUS_DELETED,
            ]],
            ['role', 'in', 'range' => [
                self::ROLE_GUESS,
                self::ROLE_DEVELOPER,
                self::ROLE_ADMINISTRATOR,
                self::ROLE_MODERATOR,
                self::ROLE_WRITER,
            ]],
            ['gender', 'in', 'range' => [
                self::GENDER_MALE,
                self::GENDER_FEMALE,
            ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        switch (Yii::$app->controllerNamespace) {
            case 'frontend\controllers':
                return static::findOne([
                    'id' => $id,
                    'type' => self::TYPE_FRONTEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            case 'backend\controllers':
                return static::findOne([
                    'id' => $id,
                    'type' => self::TYPE_BACKEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            default:
                return null;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        switch (Yii::$app->controllerNamespace) {
            case 'frontend\controllers':
                return static::findOne([
                    'username' => $username,
                    'type' => self::TYPE_FRONTEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            case 'backend\controllers':
                return static::findOne([
                    'username' => $username,
                    'type' => self::TYPE_BACKEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            default:
                return null;
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        switch (Yii::$app->controllerNamespace) {
            case 'frontend\controllers':
                return static::findOne([
                    'password_reset_token' => $token,
                    'type' => self::TYPE_FRONTEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            case 'backend\controllers':
                return static::findOne([
                    'password_reset_token' => $token,
                    'type' => self::TYPE_BACKEND,
                    'status' => self::STATUS_ACTIVE
                ]);
            default:
                return null;
        }
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
