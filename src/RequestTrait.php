<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait RequestTrait
{
    /**
     * Get a subset containing the provided keys with values from the input data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function only(Request $request): array
    {
        return [];
    }

    /**
     * Get a subset containing the provided keys with values from the input data on "update".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function onlyOnUpdate(Request $request): array
    {
        return [];
    }

    /**
     * Get a subset containing the provided keys with values from the input data on "store".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function onlyOnStore(Request $request): array
    {
        return [];
    }

    /**
     * Get all the input except for a specified array of items.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function except(Request $request): array
    {
        return [];
    }

    /**
     * Get all the input except for a specified array of items on "update".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function exceptOnUpdate(Request $request): array
    {
        return [];
    }

    /**
     * Get all the input except for a specified array of items on "store".
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function exceptOnStore(Request $request): array
    {
        return [];
    }

    /**
     * Perform Hook after each request validation.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function afterValidation(Request $request): void
    {
        //
    }

    /**
     * Perform Hook after "update" request validation.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return void
     */
    protected function afterValidationOnUpdate(Request $request, Model $object): void
    {
        //
    }

    /**
     * Perform Hook after "store" request validation.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function afterValidationOnStore(Request $request): void
    {
        //
    }

    /**
     * Perform Hook after each Model storing.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return void
     */
    protected function afterSave(Request $request, Model $object): void
    {
        //
    }

    /**
     * Perform Hook after "update" Model storing.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return void
     */
    protected function afterUpdate(Request $request, Model $object): void
    {
        //
    }

    /**
     * Perform Hook after "store" Model storing.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return void
     */
    protected function afterStore(Request $request, Model $object): void
    {
        //
    }

    /**
     * Perform Hook after remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|int               $id
     *
     * @return void
     */
    protected function afterDestroy(Request $request, string|int $id): void
    {
        //
    }

    /**
     * Perform Hook after force a hard delete on a soft deleted model.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|int               $id
     *
     * @return void
     */
    protected function afterForceDelete(Request $request, string|int $id): void
    {
        //
    }

    /**
     * Perform Hook after "restore" Model storing.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $object
     *
     * @return void
     */
    protected function afterRestore(Request $request, Model $object): void
    {
        //
    }
}
