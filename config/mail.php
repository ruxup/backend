<?php

return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
    'port' => env('MAIL_PORT', 2525),
    'from' => [
        'address' => 'from@example.com',
        'name' => 'Example',
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME', '974f423fe10a58'),
    'password' => env('MAIL_PASSWORD', '9bbe32d53be65a'),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false

];
