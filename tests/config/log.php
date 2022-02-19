<?php
use Psr\Log\LogLevel;
return [
    "type" => "log",
    "config" => [
        [
            'name' => 'warnapp',
            'level' => LogLevel::WARNING,
            'filename' => dirname(__DIR__) . '/logs/' . APP_NAME . '.txt',
            'format' => null,
            'date_format' => 'd/m/Y H:i:s',
            'max_files' => 6,
            'file_perms' => 0777,
            "adapter" => "file"
        ],
        [
            'name' => 'error',
            'level' => LogLevel::ERROR,
            'filename' => dirname(__DIR__) . '/logs/' . APP_NAME . '.txt',
            'format' => "[%datetime%] %level_name% %message%\n",
            'date_format' => 'd/m/Y H:i:s ',
            'max_files' => 3,
            'file_perms' => 0755,
            "adapter" => "file"
        ],
        [
            'name' => 'infodefault',
            'level' => LogLevel::INFO,
            'filename' => dirname(__DIR__) . '/logs/' . APP_NAME . '.txt',
            'format' => null, // '[%datetime%] %level_name%: %message%\n\n\n\n',
            'date_format' => 'd/m/Y H:i:s',
            'max_files' => 10,
            'file_perms' => 0755,
            "adapter" => "file"
        ],
        [
            'name' => 'info',
            'level' => LogLevel::INFO,
            'channel' => APP_NAME,
            'filename' => dirname(__DIR__) . '/logs/' . APP_NAME . '_2.txt',
            'date_format' => 'd/m/Y H:i:s',
            'max_files' => 10,
            'file_perms' => 0755,
            "adapter" => "file"
        ],
        [
            'name' => 'errorapp',
            'level' => LogLevel::ERROR,
            'channel' => APP_NAME,
            'filename' => dirname(__DIR__) . '/logs/' . APP_NAME . '_e2.txt',
            'date_format' => 'd/m/Y H:i:s',
            'max_files' => 10,
            'file_perms' => 0755,
            "adapter" => "file"
        ]
    ]
];
