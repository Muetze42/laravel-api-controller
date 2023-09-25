<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait FilterTrait
{
    /**
     * Determine allowed filters for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function indexFilter(Request $request): array
    {
        return [];
    }

    /**
     * Determine allowed like-filters for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function indexLikeFilter(Request $request): array
    {
        return [];
    }

    /**
     * Determine allowed has filters for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function indexHasFilter(Request $request): array
    {
        return [];
    }

    /**
     * Determine allowed has-like filters for "index".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function indexHasLikeFilter(Request $request): array
    {
        return [];
    }
}
