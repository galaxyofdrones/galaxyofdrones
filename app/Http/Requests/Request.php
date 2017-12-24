<?php

namespace Koodilab\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

abstract class Request extends FormRequest
{
    /**
     * Get the rule parameters.
     *
     * @return array
     */
    protected function onlyRules()
    {
        if (method_exists($this, 'rules')) {
            return $this->only(array_keys($this->rules()));
        }

        return [];
    }

    /**
     * Get the rule paramters with keys.
     *
     * @param array|mixed $keys
     *
     * @return array
     */
    protected function onlyRulesWith($keys)
    {
        $keys = is_array($keys)
            ? $keys
            : func_get_args();

        return array_merge($this->onlyRules(), $this->only($keys));
    }

    /**
     * Get the rule paramters except keys.
     *
     * @param array|mixed $keys
     *
     * @return array
     */
    protected function onlyRulesExcept($keys)
    {
        $keys = is_array($keys)
            ? $keys
            : func_get_args();

        $results = $this->onlyRules();

        Arr::forget($results, $keys);

        return $results;
    }
}
