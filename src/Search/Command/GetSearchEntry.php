<?php namespace Anomaly\SearchModule\Search\Command;

use Anomaly\SearchModule\Search\Contract\SearchItemInterface;
use Anomaly\Streams\Platform\Model\EloquentModel;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GetSearchEntry
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class GetSearchEntry
{

    /**
     * The search item.
     *
     * @var SearchItemInterface
     */
    protected $item;

    /**
     * Create a new GetSearchEntry instance.
     *
     * @param SearchItemInterface $item
     */
    public function __construct(SearchItemInterface $item)
    {
        $this->item = $item;
    }

    /**
     * Handle the command.
     *
     * @param  Container                                                                         $container
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function handle(Container $container)
    {
        if ($type = $this->item->getEntryType()) {

            /* @var Builder|EloquentModel $model */
            $model = $container->make($type);

            return $model->find($this->item->getEntryId());
        }

        return null;
    }
}
