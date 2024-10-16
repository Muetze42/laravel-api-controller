<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends AbstractController
{
    /**
     * The inherit class.
     *
     * @return string
     */
    protected function inheritClass(): string
    {
        return __CLASS__;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function index(Request $request, ...$arguments): mixed
    {
        return $this->indexAction($request);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function show(Request $request, ...$arguments): mixed
    {
        return $this->showAction($request, $arguments[0]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function update(Request $request, ...$arguments): mixed
    {
        return $this->updateAction($request, $arguments[0]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Request $request, ...$arguments): Response|ResponseFactory
    {
        return $this->destroyAction($request, $arguments[0]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function store(Request $request, ...$arguments): mixed
    {
        return $this->storeAction($request);
    }

    /**
     * Restore the specified soft-deleted resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function restore(Request $request, ...$arguments): mixed
    {
        return $this->restoreAction($request, $arguments[0]);
    }

    /**
     * Force a hard delete on a soft deleted model.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function forceDelete(Request $request, ...$arguments): Response|ResponseFactory
    {
        return $this->forceDeleteAction($request, $arguments[0]);
    }
}
