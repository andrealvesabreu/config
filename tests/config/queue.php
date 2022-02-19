<?php
return [
    "type" => "queue",
    "config" => [
        [
            'name' => 'redisteste',
            'driver' => 'redis',
            'host' => 'localhost',
            'persisted' => true,
            'port' => 6379,
            'database' => 16,
            'consumer' => [
                'Test',
                'fn'
            ]
        ],
        [
            'name' => 'track',
            'driver' => 'rabbit',
            'host' => 'localhost',
            'vhost' => 'services',
            'port' => 5672,
            'user' => 'admin',
            'pass' => 'admin',
            'exchange' => 'tracking',
            'queue_type' => 'direct',
            'persisted' => true,
            'consumer' => 'Test'
        ]
    ]
];