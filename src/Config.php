<?php

declare(strict_types=1);

// Copyright (c) 2022 André Alves
// 
// This software is released under the MIT License.
// https://opensource.org/licenses/MIT

namespace Inspire\Config;

use Inspire\Support\Arrays;

class Config
{

    /**
     * Array to load application config
     *
     * @var array
     */
    protected static ?array $config = [];

    /**
     * Internal message for unexpected errors
     *
     * @var array
     */
    private static array $internalError = [];

    /**
     * Get data from configuration
     *
     * @param string $item
     */
    public static function get(string $item = null)
    {
        try {
            return Arrays::get(self::$config, $item);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Add data from input array to configuration
     *
     * @param array $data
     * @return int number of configurations added
     */
    public static function addConfig(array $data, string $type, bool $check = false): int
    {
        $count = 0;
        if (! $check || ($check && self::checkConfiguration([
            'type' => $type,
            'config' => $data
        ]))) {
            foreach ($data as $idx => $imp) {
                if (! isset($imp['name'])) {
                    self::$internalError[] = "You must provide a identifier name for every configuration! Index {$idx}";
                    continue;
                }
                Arrays::set(self::$config, "{$type}.{$imp['name']}", $imp);
                $count ++;
            }
        }
        return $count;
    }

    /**
     * Load all configuration files from specified folder
     * The basename of each file will be the name of the group
     *
     * @param string $path
     * @return int total of configurations loaded
     */
    public static function loadFromFolder(string $path, bool $check = false): int
    {
        $count = 0;
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        if (file_exists($path) && is_dir($path) && is_readable($path)) {
            foreach (glob("{$path}/*.php") as $file) {
                $data = self::getFromFile($file);
                $count += self::addConfig($data['config'], $data['type'], $check);
            }
        }
        return $count;
    }

    /**
     *
     * Load configurations from specified file
     * The basename of this file will be the name of the group
     *
     * @param string $path
     * @return int total of configurations loaded
     */
    public static function loadFromFile(string $path, bool $check = false): int
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $data = self::getFromFile($path);
        return self::addConfig($data['config'], $data['type'], $check);
    }

    /**
     * Load array configuration from file
     *
     * @param string $path
     * @throws \Exception
     * @return array|NULL Configuration data
     */
    private static function getFromFile(string $path): ?array
    {
        if (! file_exists($path) || ! is_readable($path)) {
            self::$internalError[] = "Could not find {$path} configuration file.";
            return null;
        }
        /**
         * Import configuration file
         */
        $data = require $path;
        if (! is_array($data) || ! isset($data['type'])) {
            self::$internalError[] = "Could not determine type of " . basename($path) . " configuration. Missing 'type' field!";
        } else if (! isset($data['config'])) {
            self::$internalError[] = "Could not find " . basename($path) . " configuration detals. Missing section 'config'!";
        } else {
            return $data;
        }
    }

    /**
     * Check configuration in $config var based on JSON schema
     *
     * @param array $config
     * @return bool
     */
    public static function checkConfiguration(array $config): bool
    {
        try {
            $schema = dirname(__DIR__) . "/schemas/{$config['type']}.json";
            if (! file_exists($schema) || ! is_readable($schema)) {
                self::$internalError[] = "Could not find {$schema} configuration file.";
            }
            if (! JsonValidator::validateJson(json_encode($config, JSON_UNESCAPED_UNICODE), $schema)) {
                if (JsonValidator::hasErrors()) {
                    self::$internalError[] = "Errors when validating {$schema}";
                    self::$internalError[] = array_merge(self::$internalError, JsonValidator::getReadableErrors());
                }
                return false;
            }
            return true;
        } catch (\Exception $e) {
            self::$internalError[] = "An error occurred trying to validate configuration: {$e->getTraceAsString()}";
            return false;
        }
    }

    /**
     * Check if configurations loaded are valid
     *
     * @param string $path
     * @return bool
     */
    public static function checkConfigurationFolder(string $path): bool
    {
        try {
            $ok = true;
            $path = rtrim($path, DIRECTORY_SEPARATOR);
            if (file_exists($path) && is_dir($path) && is_readable($path)) {
                foreach (glob("{$path}/*.php") as $file) {
                    $data = self::getFromFile($file);
                    $ok = $ok && self::checkConfiguration($data);
                }
            } else {
                self::$internalError[] = "Could not find {$path} configuration folder.";
            }
        } catch (\Exception $e) {
            self::$internalError[] = "An error occurred trying to validate configuration: {$e->getTraceAsString()}";
        }
        return $ok;
    }

    /**
     * Get readable errors on validation
     *
     * @return array|NULL
     */
    public static function getReadableErrors(): ?array
    {
        if (self::hasErrors()) {
            $errors = self::$internalError;
            self::$internalError = [];
            return array_unique(array_values(Arrays::rcPush($errors)));
        }
        return null;
    }

    /**
     * Check if some error was reported
     *
     * @return bool
     */
    public static function hasErrors(): bool
    {
        return ! empty(self::$internalError);
    }
}

