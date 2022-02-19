<?php
use Inspire\Config\Logger\Log;
use Psr\Log\LogLevel;
use Inspire\Config\Factories\FactoryLogger;
use Inspire\Config\Config;
use Illuminate\Contracts\Foundation\CachesConfiguration;

define('APP_NAME', 'test');
include dirname(__DIR__) . '/vendor/autoload.php';
// Load all files from foler
echo Config::loadFromFolder('config') . PHP_EOL;
// print_r(Config::get());
// Load a single file
echo Config::loadFromFile('config/s3.php') . PHP_EOL;
print_r(Config::get());
/**
 * Validate an array configuration
 */
Config::checkConfiguration([
    "type" => "s3",
    "config" => [
        [
            'name' => 'first',
            'credentials' => [
                'key' => 'your-keyyour-keyyour-keyyour-key',
                'secret' => 'your-secretyour-secretyour-secretyour-secretyour-secretyour-secretyour-secret'
            ],
            'region' => 'us-east-1',
            'version' => 'latest'
        ],
        [
            'name' => 'second',
            'credentials' => [
                'key' => 'your-keyyour-keyyour-keyyour-keyyour',
                'secret' => 'your-secretyour-secretyour-secretyour-secretyour-secretyour-secretyour-secret'
            ],
            'region' => 'sa-east-1',
            'version' => 'latest'
        ]
    ]
]);
if(Config::hasErrors()){
    var_dump(Config::getReadableErrors());
}
/**
 * Validate all file in folder configuration
 */
Config::checkConfigurationFolder('config');
if(Config::hasErrors()){
    var_dump(Config::getReadableErrors());
}
var_dump(Config::get('cache.cache4.driver'));
var_dump(Config::get('cache.i18n.host'));
var_dump(Config::get('cache.i18n'));
var_dump(Config::get('jwt.first.exp'));
var_dump(Config::get('log.warnapp.filename'));
var_dump(Config::get('s3.second.credentials.secret'));
var_dump(Config::get('filesystem.sftp3.root'));
var_dump(Config::get('filesystem.sftp3.mod.file.public'));
var_dump(Config::get('queue.track.driver'));
var_dump(Config::get('queue.track.vhost'));
var_dump(Config::get('database.mysqltest.collation'));
var_dump(Config::get('database.pgtestconfig.user'));