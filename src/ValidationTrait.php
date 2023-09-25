<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ValidationTrait
{
    /**
     * Validate the given data against the provided rules.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function validateRules(Request $request): array
    {
        return [];
    }

    /**
     * Validate the given data against the provided rules on "update".
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return array
     */
    protected function validateRulesOnUpdate(Request $request, Model $object): array
    {
        return [];
    }

    /**
     * Validate the given data against the provided rules on "store".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function validateRulesOnStore(Request $request): array
    {
        return [];
    }
}
