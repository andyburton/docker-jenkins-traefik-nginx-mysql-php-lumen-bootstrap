<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Validator;

trait ErrorCodesTrait
{

    /**
     * Format validation error response into error codes if defined
     * @param Validator $validator
     * @return array
     */
    protected function formatValidationErrors(Validator $validator)
    {
        if (defined('static::VALIDATION_CODES')) {
            return $this->validationErrorCodes($validator, static::VALIDATION_CODES);
        } else {
            return $validator->errors()->getMessages();
        }
    }

    /**
     * Return error codes from validation failures
     * @param Validator $validator
     * @param array $validationCodes
     * @return array
     */
    private function validationErrorCodes(Validator $validator, array $validationCodes)
    {
        $errorCodes = [];

        foreach ($validator->failed() as $field => $failed) {
            foreach ($failed as $rule => $params) {
                $errorCodes[] = $this->validationRuleToCode($validationCodes, $field, strtolower($rule));
            }
        }

        // Return just error codes if debug mode is disabled
        if (env('APP_DEBUG') === false) {
            return ['errors' => $errorCodes];
        }

        // Output additional information for debug mode
        $debug = [];

        foreach ($validator->failed() as $field => $failed) {
            foreach ($failed as $rule => $params) {
                $rule = strtolower($rule);
                $debug[] = [
                    'code' => $this->validationRuleToCode($validationCodes, $field, $rule),
                    'field' => $field,
                    'rule' => $rule
                ];
            }
        }

        return ['errors' => $errorCodes, 'debug' => $debug];
    }

    /**
     * Return a validation code for matching field and rule
     * Returns 0 if none is defined
     * @param array $validationCodes
     * @param $field
     * @param $rule
     * @return int
     */
    private function validationRuleToCode(array $validationCodes, $field, $rule)
    {
        if (isset($validationCodes[$field][$rule])) {
            return $validationCodes[$field][$rule];
        } else {
            return 0;
        }
    }

}