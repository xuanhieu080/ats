<?php

namespace Packages\VCore\Actions;

use Closure;


class ValidateInputAction
{
    public function handle($payload, Closure $next)
    {
        if (empty($payload['name'])) {
            return ['error' => true, 'message' => 'Please enter the name of the Module'];
        }

        $options = [];
        if (!empty($payload['option'])) {
            $options = str_split(strtolower((string) $payload['option']));
            foreach ($options as $option) {
                if (!in_array($option,  ['c', 'm', 'r', 's'])) {
                    return ['error' => true, 'message' => 'Option support C = Controller, M = Model, R = Request, S = Schema'];
                }
            }
        }

        $payload['options'] = $options;

        return $next($payload);
    }
}
