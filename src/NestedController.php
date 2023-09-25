<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class NestedController extends AbstractController
{
    use NestedTrait;

    /**
     * The Parent Model Instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|string|null|
     */
    protected Model|string|null $parentModel = null;

    /**
     * Thr Parent Gate Instance.
     *
     * @var mixed
     */
    protected mixed $parentGate;

    /**
     * The Relation between Model & Parent Model.
     *
     * @var string
     */
    protected string $relation;

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
        return $this->nestedIndexAction($request, $arguments[0]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    public function show(Request $request, ...$arguments): mixed
    {
        return $this->nestedShowAction($request, $arguments[0], $arguments[1]);
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
        // TODO: Implement update() method.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Request $request, ...$arguments): mixed
    {
        // TODO: Implement destroy() method.
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
        // TODO: Implement store() method.
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
        // TODO: Implement restore() method.
    }

    /**
     * Force a hard delete on a soft deleted model.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function forceDelete(Request $request, ...$arguments): mixed
    {
        // TODO: Implement forceDelete() method.
    }

    /**
     * Build a "show" query for the given parent resource.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function showQueryParent(Builder $query, Request $request): Builder
    {
        return $query;
    }

    /**
     * Resolve the Model, Resource and Gate instances.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function resolveInstances(Request $request): void
    {
        parent::resolveInstances($request);

        $route = $request->route();
        $routeName = $route->getName();

        $item = explode('.', $routeName)[0];
        $item = Str::studly(Str::singular($item));

        if (empty($this->parentModel)) {
            $this->parentModel = $this->appNamespace;
            if (is_dir(app_path('Models'))) {
                $this->parentModel .= 'Models\\';
            }
            $this->parentModel .= $item;
        }

        $this->parentGate = Gate::getPolicyFor($this->parentModel);

        if (empty($this->relation)) {
            $parts = explode('.', $routeName);
            $this->relation = $parts[count($parts) - 2];
        }
    }

    /**
     * Authorize a given action for the current user.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $ability
     * @param array                    $arguments
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function authorizeParentAction(Request $request, mixed $ability, array $arguments = []): void
    {
        $arguments = array_merge([$this->parentModel], $arguments);

        if (!is_null($this->gate) && method_exists($this->gate, $ability)) {
            $this->authorize($ability, $arguments);
        }
    }

    /**
     * Get the parent model by primary key or throw an exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $parentId
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getParent(Request $request, int|string $parentId): Model
    {
        $this->parentModel = $this->getInstance($this->parentModel);
        $query = ($this->parentModel)->newQueryWithoutRelationships();
        $query = $this->showQueryParent($query, $request);
        $query = $this->trashedQuery($query, $request);

        $parentObject = $query->findOrFail($parentId);

        $this->authorizeParentAction($request, 'view', [$parentObject]);

        return $parentObject;
    }
}
