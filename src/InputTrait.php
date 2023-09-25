<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait InputTrait
{
    /**
     * Input key to retrieve filters and like-filters from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function indexFilterInputKey(Request $request): string
    {
        return 'filter';
    }

    /**
     * Input key to retrieve has and has-like filters from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function indexHasInputKey(Request $request): string
    {
        return 'has';
    }

    /**
     * Input key to retrieve includes from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function includeInputKey(Request $request): string
    {
        return 'include';
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function perPageInputKey(Request $request): string
    {
        return config('api.pagination.perPage.inputKey');
    }

    /**
     * Get the default number of items to be shown per page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    protected function perPageDefault(Request $request): int
    {
        return config('api.pagination.perPage.default');
    }

    /**
     * Get the minimum number of items to be shown per page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    protected function perPageMin(Request $request): int
    {
        return config('api.pagination.perPage.min');
    }

    /**
     * Get the maximum number of items to be shown per page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    protected function perPageMax(Request $request): int
    {
        return config('api.pagination.perPage.max');
    }

    /**
     * Get the pageName attribute for pagination.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function pageName(Request $request): string
    {
        return config('api.pagination.pageName');
    }
}
