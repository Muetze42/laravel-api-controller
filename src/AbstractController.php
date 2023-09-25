<?php

namespace NormanHuth\LaravelApiController;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class AbstractController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    /**
     * API Controller Traits
     */
    use DestroyTrait;
    use FilterTrait;
    use ForceDeleteTrait;
    use IncludeTrait;
    use IndexTrait;
    use InputTrait;
    use PaginationTrait;
    use RequestTrait;
    use RestoreTrait;
    use ShowTrait;
    use StoreTrait;
    use UpdateTrait;
    use ValidationTrait;
    use VisibilityTrait;

    /**
     * The inherit class.
     *
     * @return string
     */
    abstract protected function inheritClass(): string;

    /**
     * The Model Instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|string|null|
     */
    protected Model|string|null $model = null;

    /**
     * The JsonResource Instance.
     *
     * @var \Illuminate\Http\Resources\Json\JsonResource|string|null
     */
    protected JsonResource|string|null $resource = null;

    /**
     * The called Controller action.
     *
     * @var string
     */
    protected string $viaControllerAction;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function index(Request $request, ...$arguments): mixed;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function show(Request $request, ...$arguments): mixed;

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function update(Request $request, ...$arguments): mixed;

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function destroy(Request $request, ...$arguments): mixed;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function store(Request $request, ...$arguments): mixed;

    /**
     * Restore the specified soft-deleted resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function restore(Request $request, ...$arguments): mixed;

    /**
     * Force a hard delete on a soft deleted model.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    ...$arguments
     *
     * @return mixed
     */
    abstract public function forceDelete(Request $request, ...$arguments): mixed;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected string $appNamespace;

    /**
     * Thr Gate Instance.
     *
     * @var mixed
     */
    protected mixed $gate;

    public function __construct(Request $request)
    {
        $this->initializeController($request);
    }

    /**
     * Initialize the Controller.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function initializeController(Request $request): void
    {
        $this->setLocale($request);
        $this->appNamespace = trim(app()->getNamespace());
        $this->resolveInstances($request);
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
        $inheritClass = class_basename($this->inheritClass());
        $length = (strlen($inheritClass)) * -1;

        $item = substr(class_basename(get_class($this)), 0, $length);

        if (empty($item)) {
            $pathParts = explode('/', trim($request->route()?->uri(), '/'));
            $routeParts = Arr::where($pathParts, fn (string|int $part) => !str_contains($part, '{') && !empty($part));
            $item = Str::studly(Str::singular(end($routeParts)));
        }

        if (empty($this->model)) {
            $this->model = $this->appNamespace;
            if (is_dir(app_path('Models'))) {
                $this->model .= 'Models\\';
            }
            $this->model .= $item;
        }

        $this->gate = Gate::getPolicyFor($this->model);

        if (empty($this->resource)) {
            $this->resource = $this->appNamespace . 'Http\Resources\\' . $item . 'Resource';
            if (!class_exists($this->resource)) {
                $this->resource = config('api.resource');
            }
        }
    }

    /**
     * Change application locale that will be used by the translation service provider.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function setLocale(Request $request): void
    {
        if ($locale = config('api.locale')) {
            app()->setLocale($locale);
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
     */
    protected function authorizeAction(Request $request, mixed $ability, array $arguments = []): void
    {
        $arguments = array_merge([$this->model], $arguments);

        if (!is_null($this->gate) && method_exists($this->gate, $ability)) {
            $this->authorize($ability, $arguments);
        }
    }

    /**
     * Determine using `withTrashed` on Models with SoftDeletes trait.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function withTrashed(Request $request): bool
    {
        return in_array($this->viaControllerAction, ['restore', 'forceDelete']);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request                                                              $request
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder
     */
    protected function trashedQuery(Relation|Builder $query, Request $request): Relation|Builder
    {
        if (
            $this->withTrashed($request) &&
            in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model))
        ) {
            /* @var \Illuminate\Database\Eloquent\SoftDeletes $query */
            if ($request->input('withTrashed')) {
                return $query->withTrashed();
            } elseif ($request->input('onlyTrashed')) {
                return $query->onlyTrashed();
            }
        }

        return $query;
    }

    /**
     * Get the available container instance.
     *
     * @param mixed $instance
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed|string
     */
    protected function getInstance(mixed $instance): mixed
    {
        if (is_string($instance)) {
            return app($instance);
        }

        return $instance;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $action
     *
     * @return void
     */
    protected function setCurrentAction(Request $request, string $action): void
    {
        $this->viaControllerAction = $action;
        $request->query->set('viaControllerAction', $action);
    }
}
