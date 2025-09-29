<?php

namespace app\services;

use app\models\ar\UserAR;
use app\events\ServiceUserEvent;
use app\exceptions\OperationCancelledException;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class UserService extends Component
{
    public function register(array $data): UserAR
    {
        $user = new UserAR();
        $user->scenario = UserAR::SCENARIO_REGISTER;
        $user->attributes = $data;
        $user->status = UserAR::STATUS_ACTIVE;

        $this->checkBeforeEvent(ServiceUserEvent::BEFORE_REGISTER, $user);
        $this->prepareForSave($user, true);
        $this->saveOrFail($user);
        $this->triggerEvent(ServiceUserEvent::AFTER_REGISTER, $user);

        return $user;
    }

    public function changePassword(UserAR $user, string $newPassword): void
    {
        $this->checkBeforeEvent(ServiceUserEvent::BEFORE_PASSWORD_CHANGE, $user);

        $user->password = $newPassword;
        $this->prepareForSave($user);
        $this->saveOrFail($user);

        $this->triggerEvent(ServiceUserEvent::AFTER_PASSWORD_CHANGE, $user);
    }

    public function generatePasswordResetToken(UserAR $user): string
    {
        $this->checkBeforeEvent(ServiceUserEvent::BEFORE_PASSWORD_RESET_TOKEN, $user);

        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->saveOrFail($user);

        $this->triggerEvent(ServiceUserEvent::AFTER_PASSWORD_RESET_TOKEN, $user);

        return $user->password_reset_token;
    }

    public function resetPasswordByToken(string $token, string $newPassword): bool
    {
        $user = UserAR::findOne(['password_reset_token' => $token, 'status' => UserAR::STATUS_ACTIVE]);
        if (!$user) {
            return false;
        }

        $this->checkBeforeEvent(ServiceUserEvent::BEFORE_PASSWORD_RESET, $user);

        $user->password = $newPassword;
        $user->password_reset_token = null;
        $this->prepareForSave($user);
        $this->saveOrFail($user);

        $this->triggerEvent(ServiceUserEvent::AFTER_PASSWORD_RESET, $user);

        return true;
    }

    public function activate(UserAR $user): void
    {
        $this->changeStatus(
            $user,
            UserAR::STATUS_ACTIVE,
            ServiceUserEvent::BEFORE_ACTIVATE,
            ServiceUserEvent::AFTER_ACTIVATE
        );
    }

    public function deactivate(UserAR $user): void
    {
        $this->changeStatus(
            $user,
            UserAR::STATUS_INACTIVE,
            ServiceUserEvent::BEFORE_DEACTIVATE,
            ServiceUserEvent::AFTER_DEACTIVATE
        );
    }

    public function validatePassword(UserAR $user, string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $user->password_hash);
    }

    protected function prepareForSave(UserAR $user, bool $isNew = false): void
    {
        if (!empty($user->password)) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($user->password);
            $user->password = null;
        }

        if ($isNew && empty($user->auth_key)) {
            $user->auth_key = Yii::$app->security->generateRandomString();
        }
    }

    protected function checkBeforeEvent(string $name, UserAR $user): void
    {
        $event = new ServiceUserEvent($user);
        $this->trigger($name, $event);

        if (!$event->isValid()) {
            throw new OperationCancelledException("Operation cancelled by event: $name");
        }
    }

    protected function triggerEvent(string $name, UserAR $user): void
    {
        $this->trigger($name, new ServiceUserEvent($user));
    }

    protected function saveOrFail(UserAR $user): void
    {
        if (!$user->save()) {
            $errors = implode(', ', array_map(fn($e) => implode(', ', $e), $user->errors));
            throw new Exception(Yii::t('app', 'Failed to save user: {errors}', ['errors' => $errors]));
        }
    }

    protected function changeStatus(UserAR $user, int $status, string $before, string $after): void
    {
        $this->checkBeforeEvent($before, $user);
        $user->status = $status;
        $this->saveOrFail($user);
        $this->triggerEvent($after, $user);
    }
}
