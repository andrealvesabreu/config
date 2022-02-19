<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    'sqlsrv' => [
        'type' => 'database',
        'driver' => 'sqlsrv',
        'host' => 'example.com',
        'port' => 1433,
        'database' => 'database_name',
        'username' => 'database_user',
        'password' => 'database_password'
    ],
    'mysql' => [
        'type' => 'database',
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'database',
        'username' => 'root',
        'password' => 'password',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ],
    'pgsql' => [
        'type' => 'database',
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'database' => 'fms',
        'username' => 'postgres',
        'password' => 'root',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public'
    ]
];