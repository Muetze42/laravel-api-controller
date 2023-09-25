<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;

trait NestedTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $parentId
     *
     * @return mixed
     */
    public function nestedIndexAction(Request $request, int|string $parentId): mixed
    {
        $this->setCurrentAction($request, 'index');
        $this->authorizeAction($request, 'viewAny');

        $parentObject = $this->getParent($request, $parentId);

        $query = $parentObject->{$this->relation}();

        return $this->indexCollection($request, $query);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $parentId
     * @param int|string               $id
     *
     * @return mixed
     */
    public function nestedShowAction(Request $request, int|string $parentId, int|string $id): mixed
    {
        $this->setCurrentAction($request, 'show');
        $parentObject = $this->getParent($request, $parentId);
        $query = $parentObject->{$this->relation}();
        $object = $this->getShowObject($request, $query, $id);

        return new $this->resource($object);
    }
}
