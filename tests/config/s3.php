<?php
return [
    "type" => "s3",
    "config" => [
        [
            'name' => 'first',
            'credentials' => [
                'key' => 'your-keyyour-keyyour-key',
                'secret' => 'your-secretyour-secretyour-secretyour-secretyour-secret'
            ],
            'region' => 'us-east-1',
            'version' => 'latest'
        ],
        [
            'name' => 'second',
            'credentials' => [
                'key' => 'sec-keysec-keysec-key',
                'secret' => 'sec-secretsec-secretsec-secretsec-secret'
            ],
            'region' => 'sa-east-1',
            'version' => 'latest'
        ]
    ]
];