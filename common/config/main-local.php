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
            'useFileTransport' => true,
        ],
    ],
];
