<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    'type' => 'database',
    'config' => [
        [
            'name' => 'mysqltest',
            'read' => [
                'host' => [
                    '192.168.1.1',
                    '196.168.1.2'
                ]
            ],
            'write' => [
                'host' => [
                    '196.168.1.3'
                ]
            ],
            'sticky' => true,
            'driver' => 'mysql',
            'port' => 3306,
            'database' => 'database',
            'user' => 'root',
            'pass' => 'password',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => NULL
        ],
        [
            'name' => 'pgtestconfig',
            'driver' => 'sqlsrv',
            'host' => 'localhost',
            'port' => 5432,
            'database' => 'pgtest',
            'user' => 'test',
            'pass' => 'test123',
            'charset' => 'utf8',
            'prefix' => NULL,
            'schema' => 'public'
        ]
    ]
];