<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait PaginationTrait
{
    /**
     * Get the number of items to be shown per page.
     *
     * @param Request $request
     *
     * @return int
     */
    protected function perPage(Request $request): int
    {
        $perPage = (int) $request->input(
            $this->perPageInputKey($request),
            $this->perPageDefault($request),
        );

        $min = $this->perPageMin($request);
        $max = $this->perPageMax($request);

        if ($perPage > $max) {
            $perPage = $max;
        } elseif ($perPage < $min) {
            $perPage = $min;
        }

        return $perPage;
    }
}
