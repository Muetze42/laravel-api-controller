<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait UpdateTrait
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string               $id
     *
     * @return mixed
     */
    public function updateAction(Request $request, int|string $id): mixed
    {
        $this->setCurrentAction($request, 'update');

        $this->model = $this->getInstance($this->model);

        $query = ($this->model)->newQueryWithoutRelationships();
        $query = $this->showQuery($query, $request);
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

        $this->authorizeAction($request, 'update', [$object]);

        $rules = array_merge(
            $this->validateRules($request),
            $this->validateRulesOnUpdate($request, $object)
        );
        $request->validate($rules);

        $this->afterValidation($request);
        $this->afterValidationOnUpdate($request, $object);

        $input = $request->all();

        $only = array_merge(
            $this->only($request),
            $this->onlyOnUpdate($request)
        );
        if (!count($only)) {
            $only = (new $this->model())->getFillable();
        }
        $input = Arr::only($input, $only);

        $except = array_merge(
            $this->except($request),
            $this->exceptOnUpdate($request),
            ['viaControllerAction']
        );
        $input = Arr::except($input, $except);

        $object->update($input);

        $this->afterSave($request, $object);
        $this->afterUpdate($request, $object);

        return new $this->resource($object);
    }
}
