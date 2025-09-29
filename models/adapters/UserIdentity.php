<?php

namespace app\models\adapters;

use app\models\ar\UserAR;
use yii\web\IdentityInterface;

class UserIdentity implements IdentityInterface
{
    private UserAR $user;

    public function __construct(UserAR $user)
    {
        $this->user = $user;
    }

    public static function findIdentity($id)
    {
        $user = UserAR::findOne([
            'id' => $id,
            'status' => UserAR::STATUS_ACTIVE,
        ]);
        return $user ? new static($user) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = UserAR::findOne([
            'auth_key' => $token,
            'status' => UserAR::STATUS_ACTIVE,
        ]);
        return $user ? new static($user) : null;
    }

    public static function findByUsername(string $username)
    {
        $user = UserAR::findOne([
            'username' => $username,
            'status' => UserAR::STATUS_ACTIVE,
        ]);
        return $user ? new static($user) : null;
    }

    public static function findByEmail(string $email)
    {
        $user = UserAR::findOne([
            'email' => $email,
            'status' => UserAR::STATUS_ACTIVE,
        ]);
        return $user ? new static($user) : null;
    }

    public function getId()
    {
        return $this->user->id;
    }

    public function getAuthKey()
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->user->auth_key === $authKey;
    }

    public function getUser(): UserAR
    {
        return $this->user;
    }
}
