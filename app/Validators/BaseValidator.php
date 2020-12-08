<?php

declare(strict_types=1);

namespace App\Validators;

use Exception;
use Ramsey\Uuid\Uuid;
use Validator;

abstract class BaseValidator extends Validator
{
    /**
     * @var array
     */
    private $validatorRules = [
        'structure'         => [],
        'rules'             => [],
        'additional_checks' => [],
        'messages'          => ['base' => []],
    ];

    const VALIDATOR_FAILED_FIELD = 'failed';
    const VALIDATOR_ERRORS_FIELD = 'errors';

    /**
     * @var string
     */
    private $messagesNamespace = 'base';

    /**
     * Class constructor.
     *
     * If you wish to register validator rules using traits, include the trait in your child class
     * and the trait should contain a method "register*CLASS_NAME*Rules" which will be called here
     */
    public function __construct()
    {
        foreach (class_uses($this) as $trait) {
            $traitClass = new \ReflectionClass($trait);
            $methodName = sprintf('register%sRules', $traitClass->getShortName());

            if ($traitClass->hasMethod($methodName)) {
                $this->$methodName();
            }
        }
    }

    /**
     * Register validator rules.
     *
     * @param array $structure
     * @param array $rules
     * @param array $messages
     * @param array $additionalChecks
     */
    protected function registerValidatorRules(array $structure, array $rules, $messages = [], array $additionalChecks = [])
    {
        $this->validatorRules['rules'] = array_merge($this->validatorRules['rules'], $rules);
        $this->validatorRules['additional_checks'] = array_merge($this->validatorRules['additional_checks'], $additionalChecks);
        $this->validatorRules['structure'] = array_merge($this->validatorRules['structure'], $structure);

        if (!empty($messages)) {
            if (is_object($messages)) {
                foreach ($messages as $namespace => $namespaceMessages) {
                    $this->validatorRules['messages'][$namespace] = array_merge($this->validatorRules['messages'][$namespace] ?? [], $namespaceMessages);
                }
            } else {
                $this->validatorRules['messages']['base'] = array_merge($this->validatorRules['messages'], $messages);
            }
        }
    }

    /**
     * Sets the message namespace for displaying valid error messages.
     *
     * @param string $namespace
     */
    protected function setMessageNamespace(string $namespace)
    {
        $this->messagesNamespace = $namespace;
    }

    /**
     * Validate the data and return the errors. If there are no errors, then return an empty array.
     * You can pass in rules into this method which will only be applied to the current data set,
     * rather than being registered globally.
     *
     * @param array $fields
     * @param array $rules            (Optional)
     * @param array $messages         (Optional)
     * @param array $additionalChecks (Optioanl) - Extra custom validation. See https://laravel.com/docs/5.3/validation#conditionally-adding-rules
     *
     * @return array
     */
    protected function validate(array $fields, array $rules = [], array $messages = [], array $additionalChecks = []): array
    {
        $rules = array_replace_recursive($this->validatorRules['rules'], $rules);
        $messages = array_replace_recursive($this->validatorRules['messages'][$this->messagesNamespace], $messages);
        $additionalChecks = array_merge($this->validatorRules['additional_checks'], $additionalChecks);

        $validator = Validator::make($fields, $rules, $messages);

        foreach ($additionalChecks as $check) {
            $validator->sometimes($check[0], $check[1], $check[2]);
        }

        if ($validator->fails()) {
            return [
                self::VALIDATOR_FAILED_FIELD => $validator->failed(),
                self::VALIDATOR_ERRORS_FIELD => $validator->errors()->toArray(),
            ];
        }

        return [];
    }

    /**
     * Formats errors and returns them in a custom format.
     *
     * @param array $failedValidations
     * @param array $errors
     * @param mixed $keyPrefix         (Optional)
     * @param mixed $messageArgs       (Optional)
     *
     * @return array $formattedErrors
     */
    public function formatValidationError(array $failedValidations, array $errors, $keyPrefix = null, $messageArgs = []): array
    {
        $formattedErrors = [];

        foreach ($failedValidations as $key => $codes) {
            $pos = 0;

            foreach ($codes as $code => $format) {
                $formattedErrors[] = [
                    'key'     => $keyPrefix !== null ? sprintf('%s.%s', $keyPrefix, $key) : $key,
                    'code'    => strtolower($code),
                    'message' => vsprintf($errors[$key][$pos], $messageArgs),
                ];

                ++$pos;
            }
        }

        return $formattedErrors;
    }

    /**
     * Clean the data supplied.
     *
     * @param array $data
     *
     * @return array
     */
    public function cleanData(array $data): array
    {
        return $this->cleanBodyStructure([], $data);
    }

    /**
     * Removes fields that are not supposed to be there in the data.
     * The fields that are supposed to be present are passed in a structure array.
     *
     * @param array $structure
     * @param array $data
     *
     * @throws Exception
     * @throws ValidatorException
     *
     * @return array
     */
    public function cleanBodyStructure(array $structure, array $data)
    {
        $structure = array_replace_recursive($this->validatorRules['structure'], $structure);

        foreach ($data as $key => &$value) {
            if (!isset($structure[$key])) {
                unset($data[$key]);
                continue;
            }

            if ($this->validateStructureType($structure[$key], $value) !== true) {
                throw new ValidatorException(sprintf(translate('Structure is invalid for %s'), $key));
            }
        }

        return $data;
    }

    /**
     * Validates the structure type for data passed in with boolean, string, or array fields.
     *
     * @param $type
     * @param $value
     *
     * @throws Exception
     *
     * @return bool
     */
    private function validateStructureType($type, &$value)
    {
        if (is_array($type)) {
            if (!is_array($value)) {
                return false;
            }

            foreach ($value as $subKey => &$subValue) {
                if (!isset($type[$subKey])) {
                    unset($value[$subKey]);
                    continue;
                }

                if ($this->validateStructureType($type[$subKey], $subValue) === false) {
                    return false;
                }
            }

            return true;
        }

        switch ($type) {
           case 'bool':
                return is_bool($value) === true || null === $value;
            case 'uuid':
                return Uuid::isValid($value) === true || null === $value;
            case 'string':
                return is_string($value) === true || is_int($value) === true || is_float($value) || null === $value;
            case 'number':
                return is_int($value) === true || is_float($value) || null === $value;
            case 'integer':
                return is_int($value) === true || null === $value;
            case 'array':
                return is_array($value) === true;
            case 'array:string':
                return is_array($value) === true && array_filter($value, 'is_string') === $value;
            case 'array:array':
                return is_array($value) === true && array_filter($value, 'is_array') === $value;
            case 'array:uuid':
                return is_array($value) === true && array_filter($value, ['\Ramsey\Uuid\Uuid', 'isValid']) === $value;
            default:
                throw new Exception('Invalid type');
        }
    }
}
