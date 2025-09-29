<?php

return [
    'adminEmail' => env('MAILER_ADMIN_EMAIL', 'admin@example.com'),
    'senderEmail' => env('MAILER_SENDER_EMAIL', 'noreply@example.com'),
    'senderName' => env('MAILER_SENDER_NAME', 'Example.com'),

    'smsPilotApiKey' => env('SMSPILOT_API_KEY', 'XYZ'),
    'smsPilotSenderId'   => env('SMSPILOT_SENDER_ID', 'INFORM'),
];
