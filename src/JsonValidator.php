<?php
declare(strict_types = 1);
namespace Inspire\Config;

use Exception;
use Opis\JsonSchema\Errors\ {
    ValidationError,
    ErrorFormatter
};
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\SchemaLoader;
use Inspire\Support\Message\System\SystemMessage;
use Inspire\Support\Arrays;

/**
 * Description of JsonValidator
 *
 * @author aalves
 */
class JsonValidator
{

    /**
     * Language to use on messages
     *
     * @var string
     */
    private static string $lang = 'en_US';

    /**
     * Loader for related schemas
     *
     * @var SchemaLoader|null
     */
    private static ?SchemaLoader $loader = null;

    /**
     * Schema resolver for schemas loaded
     *
     * @var SchemaResolver|null
     */
    private static ?SchemaResolver $resolver = null;

    /**
     * Registered errors
     *
     * @var array
     */
    private static ?ValidationError $errors = null;

    /**
     * List of errors in human readable format
     *
     * @var array
     */
    private static array $readableErrors = [];

    /**
     * List of errors with SystemMessage
     *
     * @var array
     */
    private static array $systemErrors = [];

    /**
     * Max erros to report
     *
     * @var int
     */
    private static int $maxErrors = 5;

    /**
     *
     * @var array
     */
    private static array $readable_messages = [
        'pt_US' => [
            'minItems' => "O grupo '?' deve ter no mínimo ? elementos, mas apenas ? foram informados.",
            'maxItems' => "O grupo '?' deve ter no máximo ? elementos, mas foram informados ?.",
            'minLength' => "O campo '?' deve ter no mínimo ? caracteres, mas apenas ? foram informados.",
            'maxLength' => "O campo '?' deve ter no máximo ? caracteres, mas foram informados ?.",
            'format' => "O campo '?' deve ser preenchido no formato '?'.",
            'minimum' => "O valor do campo '?' deve ser no mínimo ?. Você informou ?.",
            'maximum' => "O valor do campo '?' deve ser no máximo ?. Você informou ?.",
            'pattern' => "O valor do campo '?' deve obedecer a E.R. ?.",
            'required' => "O campo '?' é obrigatório.",
            'type' => "O campo '?' deve ser um de [?], mas foi informado '?'.",
            'enum' => "O campo '?' deve ser preenchido com um dos seguintes valores: ?.",
            'oneOf' => "O campo '?' não corresponde a nenhum dos esquemas disponíveis.",
            'multipleOf' => "O campo '?' deve ser multiplo de ?."
        ],
        'en_US' => [
            'minItems' => "The group '?' must have at least ? elements, but only ? was informed.",
            'maxItems' => "The group '?' must have at most ? elements, but ? was informed.",
            'minLength' => "The field '?' must have at least ? characters, but only ? was informed.",
            'maxLength' => "The field '?' must have at most ? characters, but ? was informed.",
            'format' => "The field '?' must be filled in '?' format.",
            'minimum' => "The value of the field '?' must be at least ?. ? was informed.",
            'maximum' => "The value of the field '?' must be at most ?. ? was informed.",
            'pattern' => "The value of the field '?' must match E.R. ?.",
            'required' => "The field '?' is required.",
            'type' => "The field '?' must be one of [?], but it was informed '?'.",
            'enum' => "The field '?' must be filled with one of the following values: ?.",
            'oneOf' => "The field '?' does not match any of the available schemes.",
            'multipleOf' => "The field '?' must be multiple of ?."
        ]
    ];

    /**
     * Validate JSON string using a JSON schema
     *
     * @param string $data
     * @param string $schema
     * @param int $max_errors
     * @return boolean
     * @throws \Exception
     */
    public static function validateJson(string $data, string $schema, int $max_errors = 1): bool
    {
        try {
            $vdata = json_decode($data);
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception('Input data is not a JSON string');
            }
            /**
             * Schema can be a file
             */
            if (preg_match('/^[\/\w\-. ]+$/', $schema)) {
                if (! file_exists($schema)) {
                    throw new Exception('Schema file not found');
                }
                $vschema = json_decode(file_get_contents($schema));
                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new Exception('Invalid schema file');
                }
            } else {
                $vschema = json_decode($schema);
                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new Exception('Invalid schema string');
                }
            }
            /**
             * Apply JSON schema
             */
            $validator = new \Opis\JsonSchema\Validator();
            /**
             * Set max errors for reporting
             */
            $validator->setMaxErrors(self::$maxErrors);
            /**
             * Set loader
             */
            if (self::$loader !== null) {
                $validator->setLoader(self::$loader);
            }
            /**
             * Set resolver
             */
            if (self::$resolver !== null) {
                $validator->setResolver(self::$resolver);
            }
            /**
             * Check data with json schema
             */
            self::$errors = $validator->dataValidation($vdata, $vschema);
            if (self::hasErrors()) {
                self::parseErrors();
            }
            return ! self::hasErrors();
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Check if errors data are filled
     *
     * @return bool
     */
    public static function hasErrors(): bool
    {
        return self::$errors !== null;
    }

    /**
     * Returns errors as validator fills
     *
     * @return array|null
     */
    public static function getErrors(): ?array
    {
        return is_array(self::$errors) && ! empty(self::$errors) ? self::$errors : null;
    }

    /**
     * Return readable errors
     *
     * @return array|NULL
     */
    public static function getReadableErrors(): ?array
    {
        return is_array(self::$readableErrors) && ! empty(self::$readableErrors) ? self::$readableErrors : null;
    }

    /**
     * Return system errors
     *
     * @return array|NULL
     */
    public static function getSystemErrors(): ?array
    {
        return is_array(self::$systemErrors) && ! empty(self::$systemErrors) ? self::$systemErrors : null;
    }

    /**
     * Return parsed error list
     *
     * @param array $errorList
     * @return array
     */
    private static function parseErrors(?ValidationError $errorList = null)
    {
        $error = self::$errors;
        $formatter = new ErrorFormatter();

        $custom = function (ValidationError $error) use ($formatter) {
            $fieldMessage = null;
            $errMessage = null;
            switch ($error->keyword()) {
                /**
                 * Array limitations
                 */
                case 'minItems':
                case 'maxItems':
                    $values = array_values($error->args());
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    array_unshift($values, $fieldMessage);
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/",
                        "/\?/"
                    ], //
                    $values, //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * String size limitations
                 */
                case 'minLength':
                case 'maxLength':
                    $values = array_values($error->args());
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    array_unshift($values, $fieldMessage);
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/",
                        "/\?/"
                    ], //
                    $values, //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * String format error
                 */
                case 'format':
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/"
                    ], //
                    [
                        $fieldMessage,
                        $error->args()[$error->keyword()]
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Number limitations
                 */
                case 'minimum':
                case 'maximum':
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/",
                        "/\?/"
                    ], //
                    [
                        $fieldMessage,
                        $error->args()[substr($error->keyword(), 0, 3)],
                        $error->data()->value()
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Missing required fields
                 */
                case 'required':
                    $errMessage = '';
                    foreach ($error->args()['missing'] as $miss) {
                        $fieldMessage = implode('->', $error->data()->fullPath()) . "->" . $miss;

                        if (empty($errMessage)) {
                            $errMessage = str_replace('?', //
                            $fieldMessage, //
                            self::$readable_messages[self::$lang]['required']);
                        } else {
                            $errMessage .= '__SPLIT__' . str_replace('?', //
                            $fieldMessage, //
                            self::$readable_messages[self::$lang]['required']);
                        }
                    }
                    break;
                /**
                 * Type errors
                 */
                case 'type':
                    $info = $error->args();
                    if (is_array($info['expected'])) {
                        $info['expected'] = implode(', ', $info['expected']);
                    }
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/",
                        "/\?/"
                    ], //
                    array_merge([
                        $fieldMessage
                    ], $info), //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Error pattern matches
                 */
                case 'pattern':
                    print_r($error->args());
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/"
                    ], //
                    [
                        $fieldMessage,
                        $error->args()['pattern']
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Enumeration errors
                 */
                case 'enum':
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $schema = $error->schema()->info();
                    $errMessage = preg_replace([
                        "/\?/",
                        "/\?/"
                    ], //
                    [
                        $fieldMessage,
                        '[' . implode(',', $schema->data()->enum) . ']'
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Enumeration errors
                 */
                case 'oneOf':
                    $schema = $error->schema()->info();
                    $fieldMessage = implode('->', $error->data()->fullPath());
                    $errMessage = preg_replace([
                        "/\?/"
                    ], //
                    [
                        $fieldMessage
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
                    break;
                /**
                 * Multiple of to validate numbers
                 */
                case 'multipleOf':
                    $schema = $error->schema()->info();
                    return preg_replace([
                        "/\?/",
                        "/\?/"
                    ], //
                    [
                        implode('->', $error->data()->fullPath()),
                        $schema->data()->multipleOf
                    ], //
                    self::$readable_messages[self::$lang][$error->keyword()], //
                    1);
            }
            if ($errMessage !== null) {
                $sysErr = new SystemMessage($errMessage, //
                $error->keyword(), //
                1, //
                false);
                $sysErr->setExtra([
                    'field' => $fieldMessage,
                    'rule' => $error->keyword()
                ]);
                self::$systemErrors[] = $sysErr;
                return explode('__SPLIT__', $errMessage);
            }
            return $error->message();
        };
        // $print($formatter->format($error, true, $custom, $custom_key));
        // print_r(array_values($formatter->format($error, false, $custom)));
        self::$readableErrors = array_values(Arrays::rcPush($formatter->format($error, false, $custom)));
    }

    /**
     * Get current language from error reporting
     *
     * @return string
     */
    public static function getLanguage()
    {
        return self::$lang;
    }

    /**
     * Set language for error reporting
     *
     * @param string $lang
     * @throws \Exception
     */
    public static function setLanguage(string $lang)
    {
        if ($lang != 'pt_BR' && $lang != 'en_US') {
            throw new \Exception('There are only two languages available for JsonValidator: pt_BR and en_US.');
        }
        self::$lang = $lang;
    }

    /**
     * Get max errors reporting
     *
     * @return int
     */
    public static function getMaxErrors(): int
    {
        return self::$maxErrors;
    }

    /**
     * Set max errors for error reporting
     *
     * @param string $lang
     * @throws \Exception
     */
    public static function setMaxErrors(int $max)
    {
        self::$maxErrors = $max;
    }

    /**
     * Get current schema loader
     *
     * @return SchemaLoader|NULL
     */
    public static function getLoader(): ?SchemaLoader
    {
        return self::$loader;
    }

    /**
     * Set a schema loader
     *
     * @param SchemaLoader $loader
     */
    public static function setLoader(SchemaLoader $loader)
    {
        self::$loader = $loader;
    }

    /**
     * Get current schema resolver
     *
     * @return SchemaResolver|NULL
     */
    public static function getResolver(): ?SchemaResolver
    {
        return self::$resolver;
    }

    /**
     * Set current schema resolver
     *
     * @param SchemaResolver $resolver
     */
    public static function setResolver(SchemaResolver $resolver)
    {
        self::$resolver = $resolver;
    }
}