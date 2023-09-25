<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait RestoreTrait
{
    /**
     * Restore the specified soft-deleted resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $id
     *
     * @return mixed
     */
    public function restoreAction(Request $request, int|string $id): mixed
    {
        $this->setCurrentAction($request, 'restore');
        $this->model = $this->getInstance($this->model);

        $query = ($this->model)->newQueryWithoutRelationships();

        $query = $this->showQuery($query, $request);
        $object = $query->find($id);

        if ($object) {
            $this->authorizeAction($request, 'restore', [$object]);
        }

        if (!$object) {
            /* @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\SoftDeletes $query */
            $query = $query->withTrashed();

            /* @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\SoftDeletes $object */
            $object = $query->findOrFail($id);

            $this->authorizeAction($request, 'restore', [$object]);

            $object->restore();

            $this->afterRestore($request, $object);
        }

        return new $this->resource($object);
    }
}
