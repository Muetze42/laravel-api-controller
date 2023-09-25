<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait StoreTrait
{
    /**
     * Determine the method wich used for the "store" route.
     *
     * @var string
     */
    protected string $methodToStore = 'firstOrCreate';

    public function storeAction(Request $request): mixed
    {
        $this->setCurrentAction($request, 'store');
        $this->authorizeAction($request, 'create');

        $rules = array_merge(
            $this->validateRules($request),
            $this->validateRulesOnStore($request)
        );
        $request->validate($rules);

        $this->afterValidation($request);
        $this->afterValidationOnStore($request);

        $this->model = $this->getInstance($this->model);

        $input = $request->all();

        $only = array_merge(
            $this->only($request),
            $this->onlyOnStore($request)
        );
        if (!count($only)) {
            $only = (new $this->model())->getFillable();
        }
        $input = Arr::only($input, $only);

        $except = array_merge(
            $this->except($request),
            $this->exceptOnStore($request),
            ['viaControllerAction']
        );
        $input = Arr::except($input, $except);

        $object = $this->model->{$this->methodToStore}($input);

        $this->afterSave($request, $object);
        $this->afterStore($request, $object);

        return $object;
    }
}
