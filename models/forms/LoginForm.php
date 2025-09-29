<?php

namespace app\models\forms;

use app\models\adapters\UserIdentity;
use Yii;
use yii\base\Model;
use app\services\UserService;

class LoginForm extends Model
{
    public string $username = '';
    public string $password = '';
    public bool $rememberMe = true;

    private ?UserIdentity $_user = null;

    public UserService $userService;

    public function __construct(UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $userIdentity = $this->getUser();
        if (!$userIdentity || !$this->userService->validatePassword($userIdentity->getUser(), $this->password)) {
            $this->addError($attribute, Yii::t('app', 'Incorrect username or password.'));
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600*24*30 : 0
            );
        }
        return false;
    }

    public function getUser(): ?UserIdentity
    {
        if ($this->_user === null) {
            $this->_user = UserIdentity::findByUsername($this->username);
        }
        return $this->_user;
    }
}
