<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
            // unique CSRF cookie parameter for backend (set by kartik-v/yii2-app-practical)
            'csrfParam' => '_backendInlislite',
        ],
        // unique identity cookie parameter for backend (set by kartik-v/yii2-app-practical)
        'user' => [
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
                'path' => '/inlislitev3/backend' // set it to correct path for backend app.
            ]
        ]
    ],
];
