<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ForceDeleteTrait
{
    /**
     * Force a hard delete on a soft deleted model.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function forceDeleteAction(Request $request, int|string $id): Response|ResponseFactory
    {
        $this->setCurrentAction($request, 'forceDelete');
        $this->model = $this->getInstance($this->model);

        $query = ($this->model)->newQueryWithoutRelationships();
        /* @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\SoftDeletes $query */
        $query = $query->withTrashed();
        $query = $this->showQuery($query, $request);

        /* @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\SoftDeletes $object */
        $object = $query->findOrFail($id);
        $this->authorizeAction($request, 'forceDelete', [$object]);

        $object->forceDelete();

        $this->afterForceDelete($request, $id);

        return response(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
