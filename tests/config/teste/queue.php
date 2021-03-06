<?php

/*
 * ATENTION!!!
 * File auto generated by Inspire\System\Config
 */
return [
    'teste' => [
        'type' => 'queue',
        'driver' => 'redis',
        'host' => 'localhost',
        'port' => 6379,
        'pass' => '',
        'database' => 16,
        'producer' => 'Brudam',
        'processor' => 'Brudam'
    ],
    'amqp' => [
        'type' => 'queue',
        'driver' => 'rabbit',
        'host' => 'localhost',
        'vhost' => 'brd',
        'port' => 6379,
        'pass' => '',
        'persisted' => false,
        'dsn' => 'amps:',
        'database' => 16,
        'producer' => 'Brudam',
        'processor' => 'Brudam'
    ]
];