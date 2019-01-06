<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 1/3/2018
 * Time: 4:12 PM
 */

namespace backend\models;

class User extends \common\models\User
{
    public $password;

    public function rules()
    {
        /**
         * @var $user User
         */
        $user = \Yii::$app->user->identity;
        $roleRange = array_keys($user->getRoleLabels());
        if (User::ROLE_DEVELOPER != $user->role) {
            unset($roleRange[User::ROLE_DEVELOPER]);
        }
        return array_merge(
            parent::rules(),
            [
                [['email'], 'trim'],
                [['email', 'type', 'status', 'role'], 'required'],
                [['email', 'first_name', 'last_name'], 'string', 'max' => 255],
                ['email', 'email'],
                [['email'], 'unique'],
                [['date_of_birth'], 'date', 'format' => 'php:Y-m-d'],
                ['password', 'string', 'min' => 6],
                ['role', 'in', 'range' => $roleRange],
            ],
            $this->isNewRecord
                ? [
                    // Password is required only when creates new account.
                    ['password', 'required'],
                    // Only receives username when creates new account.
                    ['username', 'trim'],
                    ['username', 'required'],
                    ['username', 'string', 'max' => 255],
                ]
                : []
        );
    }

    public function beforeSave($insert)
    {
        if ($this->password) {
            $this->setPassword($this->password);
        }

        if ($this->isNewRecord) {
            $this->generateAuthKey();
        }

        return parent::beforeSave($insert);
    }

}