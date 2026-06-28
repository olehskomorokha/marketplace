<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key', 'email'], 'required'],
            [['username', 'password_hash', 'auth_key', 'access_token', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password_hash' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'email' => 'Email',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne((int) $id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function createUser($username, $email, $password)
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['user_id' => 'id']);
    }
}
