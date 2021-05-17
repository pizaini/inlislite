<?php
/**
 * Environment variables settings
 */
$db_host = 'localhost';
if(getenv('DB_HOST') !== false){
    $db_host = getenv('DB_HOST');
}
$db_name = 'inlislite_v3';
if(getenv('DB_NAME') !== false){
    $db_name = getenv('DB_NAME');
}
$db_port = '3306';
if(getenv('DB_PORT') !== false){
    $db_port = getenv('DB_PORT');
}
$db_username = 'user';
if(getenv('DB_USERNAME') !== false){
    $db_username = getenv('DB_USERNAME');
}
$db_password = 'password';
if(getenv('DB_PASSWORD') !== false){
    $db_password = getenv('DB_PASSWORD');
}

/**
 * Mailer
 */
$mail_host = 'localhost';
if(getenv('MAIL_HOST') !== false){
    $mail_host = getenv('MAIL_HOST');
}
$mail_username = 'email@domain.com';
if(getenv('MAIL_USERNAME') !== false){
    $mail_username = getenv('MAIL_USERNAME');
}
$mail_password = 'password';
if(getenv('MAIL_PASSWORD') !== false){
    $mail_password = getenv('MAIL_PASSWORD');
}
$mail_port = '587';
if(getenv('MAIL_PORT') !== false){
    $mail_port = getenv('MAIL_PORT');
}

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host={$db_host};dbname={$db_name};port={$db_port}",
            'username' => $db_username,
            'password' => $db_password,
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $mail_host,
                'username' => $mail_username,
                'password' => $mail_password,
                'port' => $mail_port,
                'encryption' => 'tls',
            ],
        ],
    ],
];
