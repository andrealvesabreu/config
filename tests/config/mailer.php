<?php

return [
    "type" => "mailer",
    "config" => [
        [
            'name' => 'default_smtp',
            'provider' => 'smtp',
            'driver' => 'smtp',
            'username' => '54738652943gsfdgdf',
            'password' => '54738652943gsfdgdf',
            'domain' => 'test.com.br',
            'port' => 25
        ],
        [
            'name' => 'ses_smtp',
            'provider' => 'ses',
            'driver' => 'smtp',
            'username' => '54738652943gsfdgdf',
            'password' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'ses_api',
            'provider' => 'ses',
            'driver' => 'api',
            'access_key' => '54738652943gsfdgdf',
            'secret_key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'ses_http',
            'provider' => 'ses',
            'driver' => 'http',
            'access_key' => '54738652943gsfdgdf',
            'secret_key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'gmail_smtp',
            'provider' => 'gmail',
            'driver' => 'smtp',
            'username' => '54738652943gsfdgdf',
            'password' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'mailgun_smtp',
            'provider' => 'mailgun',
            'driver' => 'smtp',
            'username' => '54738652943gsfdgdf',
            'password' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'mailgun_api',
            'provider' => 'mailgun',
            'driver' => 'api',
            'key' => '54738652943gsfdgdf',
            'domain' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'mailgun_http',
            'provider' => 'mailgun',
            'driver' => 'http',
            'key' => '54738652943gsfdgdf',
            'domain' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'mailjet_api',
            'provider' => 'mailjet',
            'driver' => 'api',
            'access_key' => '54738652943gsfdgdf',
            'secret_key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'mailjet_smtp',
            'provider' => 'mailjet',
            'driver' => 'smtp',
            'access_key' => '54738652943gsfdgdf',
            'secret_key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'postmark_smtp',
            'provider' => 'postmark',
            'driver' => 'smtp',
            'id' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'postmark_api',
            'provider' => 'postmark',
            'driver' => 'api',
            'key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'sendgrid_smtp',
            'provider' => 'sendgrid',
            'driver' => 'smtp',
            'key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'sendgrid_api',
            'provider' => 'sendgrid',
            'driver' => 'api',
            'key' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'sendinblue_smtp',
            'provider' => 'sendinblue',
            'driver' => 'smtp',
            'username' => '54738652943gsfdgdf',
            'password' => '54738652943gsfdgdf'
        ],
        [
            'name' => 'sendinblue_api',
            'provider' => 'sendinblue',
            'driver' => 'api',
            'key' => '54738652943gsfdgdf'
        ],
//         [
//             'name' => 'myownsmtp_smtp',
//             'provider' => 'myownsmtp',
//             'driver' => 'smtp',
//             'api_token' => '54738652943gsfdgdf'
//         ],
        [
            'name' => 'maildocker_api',
            'provider' => 'maildocker',
            'driver' => 'api',
            'access_key' => '54738652943gsfdgdf',
            'secret_key' => '54738652943gsfdgdf'
        ]
    ]
];
