<?php
return [
    'type' => 'cache',
    'config' => [
        [
            'name' => 'test1',
            'driver' => 'redis',
            'host' => 'localhost',
            'port' => 6379,
            'pass' => 'aeshia'
        ],
        [
            'name' => 'i18n',
            'driver' => 'redis',
            'host' => 'localhost',
            'port' => 6379,
            'pass' => "testeredis",
            'database' => 100
        ],
        [
            'name' => 'cache2',
            'driver' => 'memcached',
            'host' => 'localhost',
            'port' => 11211,
            'binary' => true,
            'prefix' => 'test_',
            'serializer' => 'json'
        ],
        [
            'name' => 'cache3',
            'driver' => 'memcached',
            'host' => 'another.host.com',
            'port' => 11211
        ],
        [
            'name' => 'cache4',
            'driver' => 'memcached',
            'host' => '4125',
            'port' => 11211
        ],
        [
            'name' => 'cache5',
            'driver' => 'memcached',
            'host' => 'hostname.host.com',
            'port' => 7485
        ],
        [
            'name' => 'cache6',
            'driver' => 'redis',
            'host' => 'localhost',
            'port' => 6379,
            'database' => 2,
            'ttl' => 9685
        ]
    ]
];
