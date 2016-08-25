<?php namespace Anomaly\SearchModule\Search\Command;

use Anomaly\SearchModule\Search\SearchCollection;
use Anomaly\SearchModule\Search\SearchCriteria;
use Anomaly\SearchModule\Search\SearchItem;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Support\Decorator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mmanos\Search\Query;
use Mmanos\Search\Search;

/**
 * Class GetSearchResults
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class GetSearchResults
{

    /**
     * The search criteria.
     *
     * @var SearchCriteria
     */
    protected $options;

    /**
     * Create a new GetSearchResults instance.
     *
     * @param SearchCriteria $criteria
     */
    public function __construct(SearchCriteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Handle the command.
     *
     * @param  ModuleCollection     $modules
     * @param  Decorator            $decorator
     * @param  Repository           $config
     * @param  Container            $container
     * @param  Request              $request
     * @param  Search               $search
     * @return LengthAwarePaginator
     */
    public function handle(
        ModuleCollection $modules,
        Decorator $decorator,
        Repository $config,
        Container $container,
        Request $request,
        Search $search
    ) {
        /* @var Query $query */
        $query = $search->index($this->criteria->option('index', 'default'));

        $constraint = $this->criteria->option('in');

        if (!empty($constraint) && is_string($constraint)) {
            $query = $query->search('stream', $constraint, ['required' => true]);
        }

        if (!empty($constraint) && is_array($constraint)) {

            /* @var Module $module */
            foreach ($modules->withConfig('search') as $module) {
                foreach ($config->get($module->getNamespace('search')) as $model => $definition) {

                    /* @var EntryInterface $model */
                    $model = $container->make($model);

                    $stream = $model->getStreamNamespace() . '.' . $model->getStreamSlug();

                    if (!in_array($stream, $constraint)) {
                        $query = $query->search('stream', $stream, ['required' => false, 'prohibited' => true]);
                    }
                }
            }
        }

        foreach ($this->criteria->getOperations() as $operation) {
            $query = call_user_func_array([$query, $operation['name']], $operation['arguments']);
        }

        $page    = $request->get('page', 1);
        $perPage = $this->criteria->option('paginate', $this->criteria->option('limit', 15));

        $query->limit(
            $perPage,
            ($page - 1) * $perPage
        );

        $collection = new SearchCollection(
            array_map(
                function ($result) use ($decorator) {
                    return $decorator->decorate(new SearchItem($result));
                },
                $query->get()
            )
        );

        return (new LengthAwarePaginator($collection, $query->count(), $perPage, $page))
            ->setPath($request->path())
            ->appends($request->all());
    }
}
