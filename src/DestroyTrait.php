<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait DestroyTrait
{
    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroyAction(Request $request, int|string $id): Response|ResponseFactory
    {
        $this->setCurrentAction($request, 'destroy');
        $this->model = $this->getInstance($this->model);

        $query = ($this->model)->newQueryWithoutRelationships();
        $query = $this->showQuery($query, $request);

        $object = $query->findOrFail($id);

        $this->authorizeAction($request, 'destroy', [$object]);

        $object->delete();

        $this->afterDestroy($request, $id);

        return response(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
