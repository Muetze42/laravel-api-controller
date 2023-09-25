<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

trait ShowTrait
{
    /**
     * Build a "show" query for the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request                                                               $request
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder
     */
    protected function showQuery(Relation|Builder $query, Request $request): Relation|Builder
    {
        return $query;
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $id
     *
     * @return mixed
     */
    public function showAction(Request $request, int|string $id): mixed
    {
        $this->setCurrentAction($request, 'show');

        $this->model = $this->getInstance($this->model);
        $query = ($this->model)->newQueryWithoutRelationships();
        $object = $this->getShowObject($request, $query, $id);

        return new $this->resource($object);
    }

    /**
     * @param \Illuminate\Http\Request                                                               $request
     * @param \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder $query
     * @param int|string                                                                             $id
     *
     * @return mixed
     */
    protected function getShowObject(Request $request, Relation|Builder $query, int|string $id): mixed
    {
        $query = $this->showQuery($query, $request);
        $query = $query->with($this->showRelationships($request));
        $query = $this->trashedQuery($query, $request);

        $object = $query->findOrFail($id)
            ->makeHidden(array_merge(
                $this->makeHiddenOnShow($request),
                $this->makeHidden($request)
            ))
            ->makeVisible(array_merge(
                $this->makeVisibleOnShow($request),
                $this->makeVisible($request)
            ));

        $this->authorizeAction($request, 'view', [$object]);

        return $object;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function showRelationships(Request $request): array
    {
        $requestRelations = explode(
            ',',
            $request->input($this->includeInputKey($request), '')
        );

        $requestRelations = array_filter($requestRelations);

        $includes = [];
        $allowed = array_merge(
            $this->allowedshowIncludes($request),
            $this->allowedIncludes($request)
        );

        foreach ($requestRelations as $relation) {
            if (in_array($relation, $allowed)) {
                $includes[] = $relation;
            }
        }

        return array_merge(
            $includes,
            $this->autoloadRelations($request),
            $this->autoloadShowRelations($request)
        );
    }
}
