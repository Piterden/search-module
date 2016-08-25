<?php namespace Anomaly\SearchModule\Search\Table;

use Anomaly\SearchModule\Search\Command\GetSearchResults;
use Anomaly\SearchModule\Search\SearchCriteria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class SearchTableEntries
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class SearchTableEntries
{

    use DispatchesJobs;

    /**
     * Handle the table entries.
     *
     * @param SearchTableBuilder $builder
     */
    public function handle(SearchTableBuilder $builder)
    {
        /* @var LengthAwarePaginator $results */
        $results = (
        new SearchCriteria(
            'results',
            function (SearchCriteria $criteria) use ($builder) {

                $criteria->search(null, $builder->getTableFilterValue('term'));

                return $this->dispatch(new GetSearchResults($criteria));
            }
        )
        )->results();

        $builder->setTableEntries($results->getCollection());
    }
}
