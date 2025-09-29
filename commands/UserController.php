<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\services\UserService;
use Yii;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate(
        string $username,
        string $email,
        string $firstName,
        string $lastName,
        string $password
    ): int {

        try {
            $user = $this->userService->register([
                'username' => $username,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $password,
            ]);

            $this->stdout(Yii::t('app', "User created successfully: ID {id}\n", ['id' => $user->id]));
            return ExitCode::OK;

        } catch (\RuntimeException $e) {
            $this->stderr(Yii::t('app', "Error creating user: {error}\n", ['error' => $e->getMessage()]));
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
