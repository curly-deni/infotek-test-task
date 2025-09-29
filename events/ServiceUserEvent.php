<?php

namespace app\events;

use app\models\ar\UserAR;
use yii\base\Event;

class ServiceUserEvent extends Event
{
    public UserAR $user;

    public const BEFORE_REGISTER = 'beforeRegister';
    public const AFTER_REGISTER = 'afterRegister';

    public const BEFORE_PASSWORD_CHANGE = 'beforePasswordChange';
    public const AFTER_PASSWORD_CHANGE = 'afterPasswordChange';

    public const BEFORE_PASSWORD_RESET_TOKEN = 'beforePasswordResetToken';
    public const AFTER_PASSWORD_RESET_TOKEN = 'afterPasswordResetToken';

    public const BEFORE_PASSWORD_RESET = 'beforePasswordReset';
    public const AFTER_PASSWORD_RESET = 'afterPasswordReset';

    public const BEFORE_ACTIVATE = 'beforeActivate';
    public const AFTER_ACTIVATE = 'afterActivate';

    public const BEFORE_DEACTIVATE = 'beforeDeactivate';
    public const AFTER_DEACTIVATE = 'afterDeactivate';

    public function __construct(UserAR $user)
    {
        $this->user = $user;
        parent::__construct();
    }
}
