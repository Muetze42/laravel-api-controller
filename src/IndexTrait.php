<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

trait IndexTrait
{
    /**
     * Build an "index" query for the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request                                                              $request
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder
     */
    protected function indexQuery(Relation|Builder $query, Request $request): Relation|Builder
    {
        return $query;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request): mixed
    {
        $this->setCurrentAction($request, 'index');
        $this->authorizeAction($request, 'viewAny');

        $object = $this->getInstance($this->model);
        $query = $object->newQueryWithoutRelationships();

        return $this->indexCollection($request, $query);
    }

    protected function indexCollection(Request $request, Relation|Builder $query): mixed
    {
        $query = $this->indexQuery($query, $request);
        $query = $this->indexFilterQuery($query, $request);
        $query = $query->with($this->indexRelationships($request));
        $query = $this->trashedQuery($query, $request);

        $order = $this->order($request);

        $paginator = $query
            ->orderBy($order['column'], $order['direction'])
            ->simplePaginate(
                $this->perPage($request),
                ['*'],
                $this->pageName($request)
            );

        $paginator->setCollection(
            $paginator->getCollection()
                ->makeHidden(array_merge(
                    $this->makeHiddenOnIndex($request),
                    $this->makeHidden($request)
                ))
                ->makeVisible(array_merge(
                    $this->makeVisibleOnIndex($request),
                    $this->makeVisible($request)
                ))
        );

        return $this->resource::collection($paginator);
    }

    /**
     * Column and direction as array "order by" clause of the query.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    #[ArrayShape(['column' => "string", 'direction' => "string"])] protected function order(Request $request): array
    {
        $model = $this->getInstance($this->model);

        return [
            'column' => $model->getKeyName(),
            'direction' => config('api.default-order-direction', 'desc')
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function indexRelationships(Request $request): array
    {
        $requestRelations = explode(
            ',',
            $request->input($this->includeInputKey($request), '')
        );

        $requestRelations = array_filter($requestRelations);

        $includes = [];
        $allowed = array_merge(
            $this->allowedIndexIncludes($request),
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
            $this->autoloadIndexRelations($request)
        );
    }

    /**
     * Build an "index" filter query for the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request                                                              $request
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder
     */
    protected function indexFilterQuery(Relation|Builder $query, Request $request): Relation|Builder
    {
        $filters = (array) $request->input($this->indexFilterInputKey($request));

        foreach ($this->indexFilter($request) as $key) {
            if (array_key_exists($key, $filters)) {
                $query->where($key, $filters[$key]);
            }
        }

        foreach ($this->indexLikeFilter($request) as $key) {
            if (array_key_exists($key, $filters)) {
                $query->where($key, 'like', '%' . $filters[$key] . '%');
            }
        }

        $filters = (array) $request->input($this->indexHasInputKey($request));

        foreach ($this->indexHasFilter($request) as $key) {
            if (array_key_exists($key, $filters)) {
                $parts = explode('.', $key);

                $relation = $parts[0];
                $column = $parts[1];
                $value = $filters[$key];

                $query->whereHas($relation, function (Builder $query) use ($column, $value) {
                    $query->where($column, $value);
                });
            }
        }

        foreach ($this->indexHasLikeFilter($request) as $key) {
            if (array_key_exists($key, $filters)) {
                $parts = explode('.', $key);

                $relation = $parts[0];
                $column = $parts[1];
                $value = $filters[$key];

                $query->whereHas($relation, function (Builder $query) use ($column, $value) {
                    $query->where($column, 'LIKE', '%' . $value . '%');
                });
            }
        }

        return $query;
    }
}
