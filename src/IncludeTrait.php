<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait IncludeTrait
{
    /**
     * Determine allowed includes.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function allowedIncludes(Request $request): array
    {
        return [];
    }

    /**
     * Determine allowed includes for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function allowedIndexIncludes(Request $request): array
    {
        return [];
    }


    /**
     * Determine allowed includes for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function allowedShowIncludes(Request $request): array
    {
        return [];
    }

    /**
     * Determine always included relations.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function autoloadRelations(Request $request): array
    {
        return [];
    }

    /**
     * Determine always included relations on "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function autoloadIndexRelations(Request $request): array
    {
        return [];
    }

    /**
     * Determine always included relations on "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function autoloadShowRelations(Request $request): array
    {
        return [];
    }
}
