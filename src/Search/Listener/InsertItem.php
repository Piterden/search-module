<?php namespace Anomaly\SearchModule\Search\Listener;

use Anomaly\SearchModule\Search\Command\GetConfig;
use Anomaly\SearchModule\Search\Index\Command\IndexEntry;
use Anomaly\Streams\Platform\Entry\Event\EntryWasSaved;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class InsertItem
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 */
class InsertItem
{

    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param EntryWasSaved $event
     */
    public function handle(EntryWasSaved $event)
    {
        $entry = $event->getEntry();

        if ($config = $this->dispatch(new GetConfig($entry))) {
            $this->dispatch(new IndexEntry($entry, $config));
        }
    }
}
