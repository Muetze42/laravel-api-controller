<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait VisibilityTrait
{
    /**
     * Make the given, typically visible, attributes hidden across the entire collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeHidden(Request $request): array
    {
        return [];
    }

    /**
     * Make the given, typically visible, attributes hidden across the entire "index" collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeHiddenOnIndex(Request $request): array
    {
        return [];
    }

    /**
     * Make the given, typically visible, attributes hidden across the entire "show" and "update" collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeHiddenOnShow(Request $request): array
    {
        return [];
    }

    /**
     * Make the given, typically hidden, attributes visible across the entire collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeVisible(Request $request): array
    {
        return [];
    }

    /**
     * Make the given, typically hidden, attributes visible across the entire "index" collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeVisibleOnIndex(Request $request): array
    {
        return [];
    }

    /**
     *  Make the given, typically hidden, attributes visible across the entire "show" and "update" collection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function makeVisibleOnShow(Request $request): array
    {
        return [];
    }
}
